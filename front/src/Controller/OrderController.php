<?php

namespace App\Front\Controller;

use App\Front\Entity\Adresse;
use App\Front\Entity\Ajouter;
use App\Front\Entity\Commande;
use App\Front\Form\DeliveryFormType;
use App\Front\Form\LivraisonFormType;
use App\Front\Form\OrderType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends FrontAbstractController
{


    #[Route('/order/historique', name: 'order_history')]
    public function orderHistory(Request $request): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Trouve les commandes passées pour cet utilisateur
        $orders = $this->commandeRepository->findBy([
            'utilisateur' => $user,
        ]);

        // Rend la vue avec les commandes
        return $this->render('order/livraison.html.twig', [
            'commandes' => $orders,
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }

    #[Route('/order/livraison', name: 'order_livraison')]
    public function validLivraison(Request $request): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Trouve les commandes passées pour cet utilisateur
        $orders = $this->commandeRepository->findBy([
            'utilisateur' => $user,
            'statut' => "En cour"
        ]);

        // Rend la vue avec les commandes
        return $this->render('order/livraison.html.twig', [
            'commandes' => $orders,
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }

    #[Route('/order/{id}/livraison', name: 'see_order_livraison')]
    public function seeLivraison(Request $request): Response
    {
        $commande = $this->commandeRepository->find($request->attributes->get('id'));

        // Rend la vue avec les commandes
        return $this->render('order/show.html.twig', [
            'commande' => $commande,
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }

    #[Route('/valid/{id}/livraison', name: 'change_status_livraison')]
    public function finLivraison(Request $request): Response
    {
        $commande = $this->commandeRepository->find($request->attributes->get('id'));
        $commande->setStatut("Livrée");
        $this->commandeRepository->save($commande, true);
        return $this->redirectToRoute('homepage');
    }

    #[Route('/order/create', name: 'order_index')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        if (!$this->getUser()){
            return $this->redirectToRoute('app_login');

        }

        $panier = [];
        $i=0;
        if($request->getSession()->get('cart') != null) {
            foreach ($request->getSession()->get('cart') as $key => $value) {
                $panier[$i]['product'] = $this->produitRepository->find($key);
                if ($panier[$i] == null) {
                    break;
                } else {
                    $panier[$i]['quantity'] = $value;
                    $i++;
                }
            }
        }

        $adresse = $this->adresseRepository->findOneBy(['utilisateur' => $this->getUser()]);

        $formOrder = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
            'adresse' => $adresse,
        ]);
        $formOrder->handleRequest($request);
        dump($formOrder->isSubmitted() && $formOrder->isValid());
        if($formOrder->isSubmitted() && $formOrder->isValid()){
            $data = $request->request->all()['order'];
            if(array_key_exists('adresse',$data)){
                $adresse = new Adresse();
                $adresse->setIntitule($data['adresse']['intitule']);
                $adresse->setVille($data['adresse']['ville']);
                $adresse->setRegion($data['adresse']['region']);
                $adresse->setCodePostal($data['adresse']['code_postal']);
                $adresse->setPays($data['adresse']['pays']);
                $adresse->setUtilisateur($this->getUser());
            }
            $adresseCommande = $this->adresseRepository->findOneBy([
                'utilisateur' => null,
                'intitule' => $adresse->getIntitule(),
                'ville' => $adresse->getVille(),
                'region' => $adresse->getCodePostal(),
                'pays' => $adresse->getPays(),
                'code_postal' => $adresse->getCodePostal()
            ]);
            if($adresseCommande == null){
                $adresseCommande = new Adresse();
                $adresseCommande->setIntitule($adresse->getIntitule());
                $adresseCommande->setVille($adresse->getVille());
                $adresseCommande->setRegion($adresse->getRegion());
                $adresseCommande->setCodePostal($adresse->getCodePostal());
                $adresseCommande->setPays($adresse->getPays());
                $adresseCommande->setUtilisateur($this->getUser());
            }
            $commande = new Commande();
            $commande->setUtilisateur($this->getUser());
            $commande->setStatut("En cour");
            $commande->setAdresse($adresseCommande);
            $this->adresseRepository->save($adresse,true);
            $this->adresseRepository->save($adresseCommande,true);
            $this->commandeRepository->save($commande, true);

            foreach ($panier as $key=>$value){
                $ajouter = new Ajouter();
                $ajouter->setCommande($commande);
                $ajouter->setProduit($value['product']);
                $ajouter->setQte($value['quantity']);
                $ajouter->setDate(new \DateTime('now'));
                $this->ajouterRepository->save($ajouter, true);
            }
            $session = $request->getSession();

            $email = (new TemplatedEmail())
                ->from('admin@doe.fr')
                ->to(new Address($this->getUser()->getUserIdentifier()))
                ->subject('merci pour votre achat')
                ->htmlTemplate('emails/order.html.twig')
                ->context([
                    'commande' => $commande,
                    'cart' => $panier,

                ])
            ;
            $mailer->send($email);

            $session->remove('cart');

            return $this->redirectToRoute('homepage');
        }

        return $this->render( 'order/index.html.twig', [
            'utilisateur' => $this->getUser(),
            'adresse' => $adresse,
            'formOrder' => $formOrder->createView(),
            'recapCart' => $panier,
            'lsession' => $request->getSession()->get('cart'),
        ]);
    }
}
