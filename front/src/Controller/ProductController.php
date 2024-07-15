<?php

namespace App\Front\Controller;

use App\Front\Service\CartService;
use Doctrine\DBAL\Connection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends FrontAbstractController
{

    #[Route('/{id}/boutique', name: 'shop_index')]
    public function index(Request $request, Connection $connection, CartService $cartService): Response
    {
        $produit = $this->produitRepository->find($request->attributes->get('id'));

        $choices = array_combine(range(1, $produit->getStock()), range(1, $produit->getStock()));

        if($produit->getStock() != 0){
            $formStock = $this->createFormBuilder()
                ->add('nombre', ChoiceType::class, [
                'choices' => $choices,
            ])
                ->add('submit', SubmitType::class, ['label' => 'AJOUTER AU PANIER'])
                ->getForm();

            $formStock->handleRequest($request);

            if($formStock->isSubmitted() && $formStock->isValid()){
                $data = $formStock->getData();
                $this->addToCart($cartService, $produit->getId(), $data['nombre']);
                $produit->setStock($produit->getStock() - $data['nombre']);
                $this->produitRepository->save($produit, true);
            }
            $formStock = $formStock->createView();
        }else {
            $formStock = null;
        }

        $sql="SELECT * FROM produit where categorie_id = :cat and id != :id order by RAND() limit 6";
        $stmt = $connection->prepare($sql);
        $stmt->bindValue("cat", $produit->getCategorie()->getId());
        $stmt->bindValue("id", $produit->getId());
        $similar = $stmt->executeQuery();
        $similar = $similar->fetchAllAssociative();

        $compose = $this->composeRepository->findBy(['produit' => $produit->getId()]);
        $image = $this->imageRepository->findBy(['produit' => $produit->getId()]);

        return $this->render('product/index.html.twig', [
            'product' => $produit,
            'formStock' => $formStock,
            'similaires' => $similar,
            'compose' => $compose,
            'images' => $image,
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }
}
