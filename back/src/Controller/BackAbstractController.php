<?php

namespace App\Back\Controller;

use App\Back\Repository\AdresseRepository;
use App\Back\Repository\AjouterRepository;
use App\Back\Repository\CategorieRepository;
use App\Back\Repository\CommandeRepository;
use App\Back\Repository\ImageRepository;
use App\Back\Repository\MailRepository;
use App\Back\Repository\ProduitRepository;
use App\Back\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

}