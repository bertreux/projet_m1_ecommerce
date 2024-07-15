<?php

namespace App\Back\Controller;

use App\Back\Entity\Ajouter;
use App\Back\Form\AjouterType;
use App\Back\Repository\AjouterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ajouter')]
class AjouterController extends AbstractController
{
    #[Route('/', name: 'app_ajouter_index', methods: ['GET'])]
    public function index(AjouterRepository $ajouterRepository): Response
    {
        return $this->render('ajouter/index.html.twig', [
            'ajouters' => $ajouterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ajouter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ajouter = new Ajouter();
        $form = $this->createForm(AjouterType::class, $ajouter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ajouter);
            $entityManager->flush();

            return $this->redirectToRoute('app_ajouter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ajouter/new.html.twig', [
            'ajouter' => $ajouter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ajouter_show', methods: ['GET'])]
    public function show(Ajouter $ajouter): Response
    {
        return $this->render('ajouter/show.html.twig', [
            'ajouter' => $ajouter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ajouter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ajouter $ajouter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AjouterType::class, $ajouter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ajouter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ajouter/edit.html.twig', [
            'ajouter' => $ajouter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ajouter_delete', methods: ['POST'])]
    public function delete(Request $request, Ajouter $ajouter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ajouter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ajouter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ajouter_index', [], Response::HTTP_SEE_OTHER);
    }
}
