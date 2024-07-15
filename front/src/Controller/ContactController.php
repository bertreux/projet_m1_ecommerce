<?php

namespace App\Front\Controller;

use App\Front\Entity\Mail;
use App\Front\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends FrontAbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class,null, []);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $mail = new Mail();
            $data = $form->getData();
            $mail->setUtilisateur($this->getUser());
            $mail->setObjet($data['objet']);
            $mail->setText($data['mail']);
            $this->mailRepository->save($mail, true);
            $this->addFlash('notice', 'Le mail a bien été envoyé');
        }
        return $this->render('contact/index.html.twig', [
            'formContact' => $form->createView(),
            'user' => $this->getUser(),
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }
}