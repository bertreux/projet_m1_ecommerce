<?php

namespace App\Front\Controller;

use App\Front\Repository\MailRepository;
use App\Front\Repository\UtilisateurRepository;
use App\Front\Service\CartService;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Front\Repository\AdresseRepository;
use App\Front\Repository\AjouterRepository;
use App\Front\Repository\CategorieRepository;
use App\Front\Repository\CommandeRepository;
use App\Front\Repository\ImageRepository;
use App\Front\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontAbstractController extends AbstractController
{
    protected $adresseRepository;
    protected $categorieRepository;
    protected $commandeRepository;
    protected $imageRepository;
    protected $utilisateurRepository;
    protected $ajouterRepository;
    protected $mailRepository;

    public function __construct(AdresseRepository $adresseRepository, CommandeRepository $commandeRepository,
                                ImageRepository   $imageRepository, CategorieRepository $categorieRepository,
                                ProduitRepository $produitRepository, UtilisateurRepository $utilisateurRepository,
                                AjouterRepository $ajouterRepository, MailRepository $mailRepository)
    {
        $this->adresseRepository = $adresseRepository;
        $this->commandeRepository = $commandeRepository;
        $this->imageRepository = $imageRepository;
        $this->categorieRepository = $categorieRepository;
        $this->produitRepository = $produitRepository;
        $this->utilisateurRepository = $utilisateurRepository;
        $this->ajouterRepository = $ajouterRepository;
        $this->mailRepository = $mailRepository;
    }

    #[Route('/mon-panier/add/{id<\d+>}', name: 'cart_add')]
    public function addToCart(CartService $cartService, int $id, int $quantity): Response
    {
        $cartService->addToCart($id, $quantity);

        return $this->redirectToRoute('cart_index');
    }
}