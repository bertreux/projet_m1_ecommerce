<?php

namespace App\Back\Controller;

use App\Back\Entity\Mail;
use App\Back\Form\MailType;
use App\Back\Repository\MailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mail')]
class MailController extends BackAbstractController
{
    #[Route('/', name: 'app_mail_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('mail/index.html.twig', [
            'mails' => $this->mailRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mail_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $mail = new Mail();
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($mail);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mail/new.html.twig', [
            'mail' => $mail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mail_show', methods: ['GET'])]
    public function show(Mail $mail): Response
    {
        return $this->render('mail/show.html.twig', [
            'mail' => $mail,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mail_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mail $mail): Response
    {
        $form = $this->createForm(MailType::class, $mail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_mail_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('mail/edit.html.twig', [
            'mail' => $mail,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mail_delete', methods: ['POST'])]
    public function delete(Request $request, Mail $mail): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mail->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($mail);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_mail_index', [], Response::HTTP_SEE_OTHER);
    }
}