<?php

namespace App\Front\Controller;

use App\Front\Form\ChangeMdpType;
use App\Front\Entity\Adresse;
use App\Front\Form\ProfilType;
use App\Front\Entity\Utilisateur;
use App\Front\Form\RegistrationFormType;
use App\Front\Repository\AdresseRepository;
use App\Front\Repository\UtilisateurRepository;
use App\Front\Security\EmailVerifier;
use App\Front\Security\UtilisateurAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends FrontAbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UtilisateurAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@doe.fr', 'admin'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/profil', name: 'app_info_profil')]
    public function profil(Request $request, AdresseRepository $adresseRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(ProfilType::class, $this->getUser(), [
            'dataAdresse' => $adresseRepository->findOneBy([
                'utilisateur' => $this->getUser(),
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all()['profil'];
            $user = $utilisateurRepository->find($this->getUser()->getId());
            $adresse = $adresseRepository->findOneBy([
                'utilisateur' => $this->getUser(),
            ]);
            if($adresse == null){
                $adresse = new Adresse();
                $adresse->setUtilisateur($user);
            }
            $user->setNom($data['nom']);
            $user->setPrenom($data['prenom']);
            $user->setTel($data['tel']);
            $user->setNom($data['nom']);
            $adresse->setIntitule($data['adresse']['intitule']);
            $adresse->setVille($data['adresse']['ville']);
            $adresse->setPays($data['adresse']['pays']);
            $adresse->setRegion($data['adresse']['region']);
            $adresse->setCodePostal($data['adresse']['code_postal']);
            $utilisateurRepository->save($user,true);
            $adresseRepository->save($adresse,true);
            $this->addFlash("info", "Information bien enregistrées");
        }
        return $this->render('profil/index.html.twig', [
            'formProfil' => $form->createView(),
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }

    #[Route('/profil/mdp', name: 'app_mdp_profil')]
    public function changeMdp(Request $request,  UserPasswordHasherInterface $userPasswordHasher, UtilisateurRepository $utilisateurRepository): Response
    {
        $user = $utilisateurRepository->find($this->getUser()->getId());
        $form = $this->createForm(ChangeMdpType::class, $this->getUser(), []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all()['change_mdp'];
            if($userPasswordHasher->isPasswordValid($user,$data['plainPassword_old'])){
                $user->setMdp(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $data['plainPassword']
                    )
                );
                $utilisateurRepository->save($user,true);
                $this->addFlash("info", "Information bien enregistrées");
            }else{
                $this->addFlash("danger", "Mauvais ancien mot de passe");
            }
        }
        return $this->render('profil/mdp.html.twig', [
            'formMdp' => $form->createView(),
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }
}
