<?php

namespace App\Back\Controller;

use App\Back\Entity\Categorie;
use App\Back\Entity\Image;
use App\Back\Repository\AdresseRepository;
use App\Back\Repository\AjouterRepository;
use App\Back\Repository\CategorieRepository;
use App\Back\Repository\CommandeRepository;
use App\Back\Repository\ImageRepository;
use App\Back\Repository\MailRepository;
use App\Back\Repository\ProduitRepository;
use App\Back\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackAbstractController extends AbstractController
{

    protected $adresseRepository;
    protected $categorieRepository;
    protected $commandeRepository;
    protected $imageRepository;
    protected $produitRepository;
    protected $utilisateurRepository;
    protected $ajouterRepository;
    protected $entityManager;
    protected $mailRepository;

    public function __construct(AdresseRepository     $adresseRepository, CommandeRepository $commandeRepository,
                                ImageRepository       $imageRepository, CategorieRepository $categorieRepository,
                                ProduitRepository $produitRepository, UtilisateurRepository $utilisateurRepository,
                                AjouterRepository $ajouterRepository, EntityManagerInterface $entityManager,
                                MailRepository $mailRepository)
    {
        $this->adresseRepository = $adresseRepository;
        $this->commandeRepository = $commandeRepository;
        $this->imageRepository = $imageRepository;
        $this->categorieRepository = $categorieRepository;
        $this->produitRepository = $produitRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->ajouterRepository = $ajouterRepository;
        $this->entityManager = $entityManager;
        $this->mailRepository = $mailRepository;
    }

    #[Route('/fetch/supprimer', name: 'app_fetch_tableau', methods: ['POST'])]
    public function supElementsInBase(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine)
    {
        $data = json_decode($request->getContent(), true);
        $idObjet = $data['parametre1'];
        $nomObjet = $data['parametre2'];
        $className = 'App\Back\Entity\\'.$nomObjet;
        $repository = $doctrine->getRepository($className);
        try {
            $objectsToDelete = [];
            foreach ($idObjet as $value) {
                $objet = $repository->find($value);
                if ($objet) {
                    if($nomObjet == "Image"){
                        unlink($objet->getUrl());
                        unlink('../../front/public/'.$objet->getUrl());
                    }
                    if($nomObjet == 'Categorie' || $nomObjet == 'Produit') {
                        foreach($objet->getImages() as $image) {
                            unlink($image->getUrl());
                            unlink('../../front/public/'.$image->getUrl());
                        }
                    }
                    $objectsToDelete[] = $objet;
                }
            }
            foreach ($objectsToDelete as $object) {
                $repository->remove($object, true);
            }
            return $this->json(1);
        } catch (\Exception $e) {
            return $this->json($e->getMessage());
        }
    }

}