<?php

namespace App\Front\Controller;

use App\Front\Entity\Adresse;
use App\Front\Entity\Produit;
use App\Front\Entity\Utilisateur;
use App\Front\Form\ChangeMdpType;
use App\Front\Form\ProfilType;
use App\Front\Form\RegistrationFormType;
use App\Front\Service\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException as ExceptionAccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

use function PHPUnit\Framework\throwException;

class RegistrationController extends FrontAbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, UserPasswordHasherInterface $userPasswordHasher,
        MailerService $mailerService,
        TokenGeneratorInterface $tokenGeneratorInterface): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //Token
            $tokenRegistration = $tokenGeneratorInterface->generateToken();

            //User
            $user->setMdp(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            //User token
            //$user->setTokenRegistration($tokenRegistration);

            //Mailer send
            /*
            $mailerService->send(
                $user->getEmail(),
                'Confirmation du compte utilisateur',
                'registration_confirmation.html.twig',
                [
                    'user' => $user,
                    'token' => $tokenRegistration,
                    'lifeTimeToken' => $user->getTokenRegistrationLifeTime()->format('d-m-Y-H-i-s')
                ]
            );
            */

            $this->utilisateurRepository->save($user, true);
            // do anything else you need here, like send an email

            $this->addFlash('success', 'Votre compte à bien été crée, veuillez vérifier votre email pour l\'activer.' );

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }
    #[Route('/verify/{token}/{id<\d+>}', name: 'account_verify', methods: ['GET'])]
    public function verify(string $token, Utilisateur $user, EntityManagerInterface $em): Response
    {

        if ($user->getTokenRegistration() !== $token) {
            throw new AccessDeniedException();
        }

        if ($user->getTokenRegistration() === null) {
            throw new AccessDeniedException();
        }

        if (new DateTime('now') > $user->getTokenRegistrationLifeTime()) {
            throw new AccessDeniedException();
        }

        $user->setIsVerified(true);
        $user->setTokenRegistration(null);
        $em->flush();



        $this->addFlash('success', 'Votre compte a bien été activé, vous pouvez maintenant vous connecter.');

        return $this->redirectToRoute('app_login');
    }

    #[Route('/profil', name: 'app_info_profil')]
    public function profil(Request $request): Response
    {
        $form = $this->createForm(ProfilType::class, $this->getUser(), [
            'dataAdresse' => $this->adresseRepository->findOneBy([
                'utilisateur' => $this->getUser(),
            ])
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all()['profil'];
            $user = $this->utilisateurRepository->find($this->getUser()->getId());
            $adresse = $this->adresseRepository->findOneBy([
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
            $this->utilisateurRepository->save($user,true);
            $this->adresseRepository->save($adresse,true);
            $this->addFlash("info", "Information bien enregistrées");
        }
        return $this->render('profil/index.html.twig', [
            'formProfil' => $form->createView(),
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }

    #[Route('/profil/mdp', name: 'app_mdp_profil')]
    public function changeMdp(Request $request,  UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->utilisateurRepository->find($this->getUser()->getId());
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
                $this->utilisateurRepository->save($user,true);
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
