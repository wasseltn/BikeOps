<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Commande;
use BikeBundle\Entity\CommandeProduit;
use BikeBundle\Entity\LineItem;
use BikeBundle\Entity\Livraison;
use BikeBundle\Entity\Panier;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Swift_Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commande controller.
 *
 * @Route("Commande")
 */
class CommandeController extends Controller
{

    public function listAction()
    {

        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(Commande::class)->findAll();

        return $this->render('commande/list.html.twig', array('commandes' => $commandes));

    }


    public function precommandeAction($panier)
    {
        $em = $this->getDoctrine()->getManager();
        $panierObj = $em->getRepository(Panier::class)->find($panier);

        return $this->render('commande/precommande.html.twig', array('panier_data' => $panier,
            'panierObj' => $panierObj));
    }

    /* Add Commande */
    public function newAction(Request $request, $panier)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $panierObj = $em->getRepository(Panier::class)->find($panier);

        $type_paiement = $request->query->get('paiement');
        $type_livraison= $request->query->get('livraison');

        /* @var Livraison $livraison*/
        $livraison = new Livraison();
        $livraison->setType($type_livraison);
        $livraison->setUtilisateur($user);
        $livraison->setAdresse($user->getAddresse());
        $livraison->setEtat(0);
        $em->persist($livraison);
        $em->flush();

        /* @var Commande $commande */
        $commande = new Commande();
        $commande->setPanier($panierObj);
        $commande->setUtilisateur($user);
        $commande->setDate(new \DateTime());
        $commande->setEtat('en_attente');
        $commande->setTypePaiment($type_paiement);
        $commande->setLivraison($livraison);

        $em->persist($commande);
        $em->flush();

        $livraison->setCommande($commande);

        /* Up to here, we added a commande **/

        $lines = $em->getRepository(LineItem::class)->findBy(array('panier' => $panier));
        /* @var $myLineitem LineItem */
        foreach ($lines as $myLineitem) {
            $commandeProduit = new CommandeProduit();
            $commandeProduit->setCommande($commande);
            $commandeProduit->setProduit($myLineitem->getProduit());
            $commandeProduit->setQuantite($myLineitem->getQuantite());

            $em->persist($commandeProduit);
            $em->remove($myLineitem);
            $em->flush();
        }

        $this->sendEmail($user, 'Confirmation de Commande', 'commande/email.html.twig');


        /* @var $panierToEdit Panier */
        $panierToEdit = $commande->getPanier();
        $panierToEdit->setTotal(0);
        $em->persist($panierToEdit);
        $em->flush();

        return $this->render('commande/final.html.twig');
    }

    public function sendEmail($utilisateur, $subject, $view)
    {

        $img = Swift_Image::fromPath('bike-logo.png');

        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('BikeOps15@gmail.com')
            ->setTo($utilisateur->getEmail());

        $cid = $mail->embed($img);

        $mail->setBody(
            $this->renderView(
                $view,
                array('username' => $utilisateur->getUsername(),
                    'imgs' => $cid)
            ),
            'text/html'
        );

        $this->get('mailer')->send($mail);
    }

    public function DeleteAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $commandeObj = $em->getRepository(commande::class)->find($id);

        $em->remove($commandeObj);
        $em->flush();

        return $this->redirectToRoute('Commande_list');

    }

    public function confirmerCommandeAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        /* @var Commande $commandeObj */
        $commandeObj = $em->getRepository(commande::class)->find($id);

        $commandeObj->setEtat('confirmée');
        $em->persist($commandeObj);
        $em->flush();

        return $this->redirectToRoute('Commande_list');

    }

    public function detailsAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        /* @var Commande $commande */
        $commande = $em->getRepository(commande::class)->find($id);

        $commandeProduits = $em->getRepository(CommandeProduit::class)->findBy(['commande' => $id]);

        $total = 0;
        /* @var CommandeProduit $cp */
        foreach ($commandeProduits as $cp) {
            $total += $cp->getProduit()->getPrix() * $cp->getQuantite();
        }
        return $this->render('commande/details.html.twig',
            array(
                'commande' => $commande,
                'commandeProduits' => $commandeProduits,
                'total' => $total));

    }

    public function pdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        /* @var Commande $commande */
        $commande = $em->getRepository(commande::class)->find($id);

        $commandeProduits = $em->getRepository(CommandeProduit::class)->findBy(['commande' => $id]);

        $total = 0;
        /* @var CommandeProduit $cp */
        foreach ($commandeProduits as $cp) {
            $total += $cp->getProduit()->getPrix() * $cp->getQuantite();
        }
        $html = $this->render('commande/toPdf.html.twig',
            array(
                'commande' => $commande,
                'commandeProduits' => $commandeProduits,
                'total' => $total));

        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            'file.pdf'
        );
    }


    public function searchAction(Request $request)
    {
        $data = $request->get('keyword');
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('
        SELECT c, u FROM BikeBundle:Commande c 
        JOIN c.utilisateur u
        WHERE u.username =:item')
            ->setParameter('item',  $data);

        $commandes = $query->getResult();

        return $this->render('commande/list.html.twig', array('commandes' => $commandes));


    }
}
