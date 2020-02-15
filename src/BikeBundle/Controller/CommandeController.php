<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Commande;
use BikeBundle\Entity\LineItem;
use BikeBundle\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Commande controller.
 *
 * @Route("Commande")
 */
class CommandeController extends Controller
{

    public function precommandeAction($panier)
    {
        $em = $this->getDoctrine()->getManager();



        return $this->render('commande/precommande.html.twig', array('panier_data' => $panier));
    }

    public function newAction(Request $request, $panier)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $panierObj = $em->getRepository(Panier::class)->find($panier);

        $commandeObj = $em->getRepository(Commande::class)
            ->findBy(['panier' => $panier, 'utilisateur' => $user ]);

        if(sizeof($commandeObj) > 0) {
            return $this->render('commande/final.html.twig');
        } else {

            $type_paiement = $request->query->get('paiement');
            // $type_livraison = $request->query->get('livraison');

            /* @var Commande $commande */
            $commande = new Commande();
            $commande->setPanier($panierObj);
            $commande->setUtilisateur($user);
            $commande->setDate(new \DateTime());
            $commande->setEtat('en_attente');
            $commande->setTypePaiment($type_paiement);
            // $commande->setTypeLivraison($livraison);

            $em->persist($commande);
            $em->flush();

            # select wanted item from shipping table to delete it
            $lines = $em->getRepository(LineItem::class)->findBy(array('panier' => $panier));

            # Fetch all them using foeach loop and delete them
            foreach ($lines as $one_prod)
            {
                # Remove item from db
                $em->remove($one_prod);
                $em->flush();
            }

            return $this->render('commande/final.html.twig');
        }

    }


}
