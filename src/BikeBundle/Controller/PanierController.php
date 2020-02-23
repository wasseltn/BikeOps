<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\LineItem;
use BikeBundle\Entity\Panier;
use BikeBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Panier controller.
 *
 * @Route("panier")
 */
class PanierController extends Controller
{

    public function showAction()
    {
        # Get object from doctrine manager
        $em = $this->getDoctrine()->getManager();
        # Get logged user then get his ['id']
        $user = $this->container->get('security.token_storage')->getToken()->getUser();

        /** Check IF user have exist cart  **/
        # select cart from database where user id equal to current logged user using [ findByUser() ]
        $user_panier = $this->getDoctrine()
            ->getRepository(Panier::class)
            ->findBy(['utilisateur' =>  $user]);

        if ( $user_panier )
        {
            # Then select all user cart products to display it to user
            // $user_panier[0] because findBy returns an Array !
            $line_items = $this->getDoctrine()
                ->getRepository(LineItem::class)
                ->findBy( array('panier' => $user_panier[0]->getId()) );

            # pass selected products to the twig page to show them
            return $this->render('panier/mycart.html.twig', array(
                'line_items'  => $line_items,
                'panier_data' => $user_panier[0],
            ));
        }

        # pass selected products to the twig page to show them
        return $this->render('panier/mycart.html.twig');
    }

    public function addAction(Request $request)
    {
        # First of all check if user logged in or not by using FOSUSERBUNDLE
        #    authorization_checker
        # if user logged in so add the selected product to his cart and redirect user to products page
        # else redirect user to login page to login first or create a new account
        $securityContext = $this->container->get('security.authorization_checker');

        # If user logged in
        if ( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') )
        {
            # Get object from doctrine manager
            $em = $this->getDoctrine()->getManager();
            # Get logged user then get his ['id']
            $user = $this->container->get('security.token_storage')->getToken()->getUser();

            # for any case we will need to select product so select it first
            # select specific product which have passed id using ['find(passedID)']

            /* @var Produit $product */
            $product = $this->getDoctrine()
                ->getRepository(Produit::class)
                ->find($request->get('productId'));

            /** Check IF user have exist cart  **/
            # select cart from database where user id equal to cureent logged user using [ findByUser() ]
            $exsit_panier = $this->getDoctrine()
                ->getRepository(Panier::class)
                ->findBy(['utilisateur' => $user]);

            # if there's no cart to this user create a new one
            if ( !$exsit_panier )
            {
                # define cart object
                $panier = new Panier();
                # set user whose own this cart
                $panier->setUtilisateur($user);

                # set initail total price for cart which equal to product price
                $panier->setTotal($product->getPrix());

                # persist all cart data to can use it in create shipping object
                $em->persist($panier);
                $em->flush();

                # create shipping object
                $lineItem = new LineItem();
                # set all its data quantity initail equal to 1 and passed product and cart created
                $lineItem->setQuantite(1);
                $lineItem->setProduit($product);
                $lineItem->setPanier($panier);

                // $product->setStock($product->getStock() - 1);
                # persist it and flush doctrine to save it
                $em->persist($lineItem);
                $em->flush();
            }
            # if user have one so just add new item price to cart price and add it to shipping
            else
            {
                # Get cart from retrived object
                /* @var Panier $panier */
                $panier = $exsit_panier[0];

                # set initail total price for cart which equal to product price
                $panier->setTotal($panier->getTotal() + $product->getPrix());
                // $product->setStock($product->getStock() - 1);
                # persist all cart data to can use it in create shipping object
                $em->persist($panier);
                # flush it
                $em->flush();

                # create shipping object
                $lineItem = new LineItem();
                # set all its data quantity initail equal to 1 and passed product and cart created
                $lineItem->setQuantite(1);
                $lineItem->setProduit($product);
                $lineItem->setPanier($panier);

                # persist it and flush doctrine to save it
                $em->persist($lineItem);
                $em->flush();
            }

            //return new Response('user id  '.$product->getId());
            return $this->redirect($this->generateUrl('bike_homepage'));
        }
        # if user not logged in yet
        else
        {
            # go to adding product form
            return $this->redirect($this->generateUrl('login'));
        }
    }

    public function removeAction($itemProduct, $itemCart)
    {
        // SIPPING LINE ITEM
        // Product produit
        # get an object from doctrine db and get Shipping Entity to work on it
        $em = $this->getDoctrine()->getManager();


        # select wanted item from shipping table to delete it
        /* @var \BikeBundle\Entity\LineItem $lineItem */
        $lineItem = $this->getDoctrine()->getRepository(LineItem::class)
            ->findOneBy(['produit' => $itemProduct, 'panier' => $itemCart]);

        # Calculate the new total price for cart by subtract deleted item price from total one
        $final_price = $lineItem->getPanier()->getTotal() - ($lineItem->getProduit()->getPrix() * $lineItem->getQuantite());

        # update the total price of cart
        $lineItem->getPanier()->setTotal($final_price);

        # Remove item from db
        $em->remove($lineItem);
        $em->flush();

        return $this->redirect($this->generateUrl('mycart'));
    }
}
