<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private RequestStack $requestStack;

    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function addToCart(int $id, int $quantity): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = $quantity;
        }
        $this->getSession()->set('cart', $cart);
    }

    public function removeToCart(int $id, ProduitRepository $produitRepository)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $product = $produitRepository->find($id);
        $product->setStock($product->getStock() + $cart[$id]);
        $produitRepository->save($product, true);
        unset($cart[$id]);
        return $this->getSession()->set('cart', $cart);
    }

    public function decrease(int $id)
    {
        $cart = $this->getSession()->get('cart', []);
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }

    public function increase(int $id)
    {
        $cart = $this->getSession()->get('cart', []);
            $cart[$id]++;
        $this->getSession()->set('cart', $cart);
    }

    public function revoveCartAll(ProduitRepository $produitRepository)
    {
        $cart = $this->getSession()->get('cart');
        foreach ($cart as $key => $value){
            $produit=$produitRepository->find($key);
            if($produit == null){
                break;
            }else{
                $produit->setStock($produit->getStock() + $value);
                $produitRepository->save($produit, true);
            }
        }
        return $this->getSession()->remove('cart');
    }

    public function getTotal(): array
    {
        $cart = $this->getSession()->get('cart');
        $cartData = [];
        if ($cart) {
            foreach ($cart as $id => $quantity) {
                $product = $this->em->getRepository(Product::class)->findOneBy(['id' => $id]);
                if (!$product) {
                    // Supprimer le produit puis continuer en sortant de la boucle
                }
                $cartData[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartData;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}