<?php

namespace App\Controller;

use App\Repository\MailRepository;
use App\Service\CartService;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\AdresseRepository;
use App\Repository\AjouterRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use App\Repository\ComposeRepository;
use App\Repository\ImageRepository;
use App\Repository\MateriauxRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontAbstractController extends AbstractController
{
    protected $adresseRepository;
    protected $categorieRepository;
    protected $commandeRepository;
    protected $imageRepository;
    protected $materiauxRepository;
    protected $produitRepository;
    protected $utilisateurRepository;
    protected $ajouterRepository;
    protected $composeRepository;
    protected $mailRepository;

    public function __construct(AdresseRepository $adresseRepository, CommandeRepository $commandeRepository,
                                ImageRepository $imageRepository, CategorieRepository $categorieRepository,
                                MateriauxRepository $materiauxRepository, ProduitRepository $produitRepository,
                                UtilisateurRepository $utilisateurRepository, AjouterRepository $ajouterRepository,
                                ComposeRepository $composeRepository, MailRepository $mailRepository)
    {
        $this->adresseRepository = $adresseRepository;
        $this->commandeRepository = $commandeRepository;
        $this->imageRepository = $imageRepository;
        $this->categorieRepository = $categorieRepository;
        $this->materiauxRepository = $materiauxRepository;
        $this->produitRepository = $produitRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->ajouterRepository = $ajouterRepository;
        $this->composeRepository = $composeRepository;
        $this->mailRepository = $mailRepository;
    }

    #[Route('/mon-panier/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, int $id, int $quantity): Response
    {
        $cartService->addToCart($id, $quantity);

        return $this->redirectToRoute('cart_index');
    }
}