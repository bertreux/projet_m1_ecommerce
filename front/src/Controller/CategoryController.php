<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\SearchType;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends FrontAbstractController
{

    private function findBySearch(Connection $connection, $form, Categorie $categorie, $session){
        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            if($form->get('ReniSearch')->isClicked()){
                $session->remove('search');
                return 0;
            }
            $session->set('search', $data);
        }else if($session->get('search') != null){
            $data = $session->get('search');
        }else {
            return -1;
        }
            $i=0;
            $where = " WHERE";
            $sql="SELECT DISTINCT produit.* FROM produit";
            if(isset($data['materiaux'][0])){
                $liste = "(";
                $i=0;
                while(isset($data['materiaux'][$i])){
                    $liste.=$data['materiaux'][$i]->getId();
                    if(isset($data['materiaux'][$i+1])){
                        $liste.=",";
                    }
                    $i++;
                }
                $liste.=")";
                $sql.=" INNER JOIN compose ON produit.id = compose.produit_id".$where." materiaux_id IN ".$liste;
                $where = " AND";
            }
            if($data['prix_min'] != null){
                $sql.=$where." prix >= ".$data['prix_min'];
                $where = " AND";
                $i++;
            }
            if($data['prix_max'] != null){
                $sql.=$where." prix <= ".$data['prix_max'];
                $where = " AND";
                $i++;
            }
            if($data['stock'] != false){
                $sql.=$where." stock > 0";
                $where = " AND";
                $i++;
            }
            if($data['description'] != null && $data['description'] != ""){
                if($data['findTextBy'] == 1){
                    $sql.=$where." description = '".$data['description']."'";
                }else if ($data['findTextBy'] == 2) {
                    $sql.=$where." description LIKE '".$data['description']."'";
                }else if($data['findTextBy'] == 3) {
                    $sql.=$where." description LIKE '%".$data['description']."'";
                }else{
                    $sql.=$where." description LIKE '%".$data['description']."%'";
                }
                $where = " AND";
                $i++;
            }
            if($data['titre'] != null && $data['titre'] != ""){
                if($data['findTextBy'] == 1){
                    $sql.=$where." nom = '".$data['titre']."'";
                }else if ($data['findTextBy'] == 2) {
                    $sql.=$where." nom LIKE '".$data['titre']."'";
                }else if($data['findTextBy'] == 3) {
                    $sql.=$where." nom LIKE '%".$data['titre']."'";
                }else{
                    $sql.=$where." nom LIKE '%".$data['titre']."%'";
                }
                $where = " AND";
                $i++;
            }
            $sql.=$where." categorie_id = ".$categorie->getId();
            if($data['trie'] != null){
                $sql.=" ORDER BY ".$data['trie']." ".$data['trieSens'];
                $i++;
            }else{
                $sql.=" ORDER BY produit.prioriter ASC and produit.stock ASC";
            }
            if($i == 0 && $session->get('search') != null){
                $session->remove('search');
            }
            $stmt = $connection->prepare($sql);
            $product = $stmt->executeQuery();
            $product = $product->fetchAllAssociative();

        return $product;
    }

    #[Route('/{id}/category', name: 'app_category')]
    public function index(Request $request, Connection $connection): Response
    {

        $session = $request->getSession();
        $materiaux = [];
        $i=0;
        if(isset($session->get('search')['materiaux'])){
            while(isset($session->get('search')['materiaux'][$i])){
                $materiaux[$i] = $this->materiauxRepository->find($session->get('search')['materiaux'][$i]->getId());
                $i++;
            }
        }

        $categorie = $this->categorieRepository->find($request->attributes->get('id'));

        $form = $this->createForm(SearchType::class, null, [
            'isCategory' => false,
            'options' => $session->get('search'),
            'listMateriaux' => $materiaux,
        ]);
        $form->handleRequest($request);

        $product = $this->findBySearch($connection, $form, $categorie, $session);
        if($product == -1) {
            $product = $this->produitRepository->findProductByCategorie($categorie);
        }else if($product == 0){
            $product = $this->produitRepository->findProductByCategorie($categorie);
            $form = $this->createForm(SearchType::class,null,[
                'isCategory' => true,
                'options' => $session->get('search'),
                'listMateriaux' => $materiaux,
            ]);
        }

        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'products' => $product,
            'categorie' => $categorie,
            'form' => $form->createView(),
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }
}
