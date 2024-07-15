<?php

namespace App\Back\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends BackAbstractController
{
    #[Route('/', name: 'homepage')]
    public function home(): Response
    {
        return $this->render('menu/index.html.twig', []);
    }
}