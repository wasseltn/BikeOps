<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Coupon;
use BikeBundle\Entity\LineItem;
use BikeBundle\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CouponController extends Controller
{
    public function showAction() {

        $em = $this->getDoctrine()->getManager();
        $couponCodes = $em->getRepository(Coupon::class)->findAll();

        return $this->render('coupon/list.html.twig', array('codes' => $couponCodes));

    }

    public function addAction () {
        $em = $this->getDoctrine()->getManager();
        $pourcentages = array(5, 10, 15, 20, 25, 30, 35, 40, 45, 50 );
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($j = 0; $j < 20; $j++) {
            $string = '';
            $randomPourcentage = array_rand($pourcentages);
            for ($i = 0; $i < 15; $i++) {
                $string .= $characters[mt_rand(0, strlen($characters) - 1)];
            }

            $coupon = new Coupon();
            $coupon->setCode($string);
            $coupon->setPourcentage($pourcentages[$randomPourcentage]);

            $em->persist($coupon);
            $em->flush();
        }
        return $this->redirectToRoute('coupon_list');
    }

    public function DeleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $couponObj = $em->getRepository(Coupon::class)->find($id);

        $em->remove($couponObj);
        $em->flush();

        return $this->redirectToRoute('coupon_list');
    }

    public function applyAction($panier, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $coupon = $request->get('coupon_code');

        /* @var Coupon $couponObj*/
        $couponObj = $em->getRepository(Coupon::class)->find($coupon);

        /* @var Panier $panierObj*/
        $panierObj = $em->getRepository(Panier::class)->find($panier);

        $reduction= ($panierObj->getTotal()) * ($couponObj->getPourcentage()/100);
        $panierObj->setTotal($panierObj->getTotal() - $reduction);

        $pourc = $couponObj->getPourcentage();

        $em->remove($couponObj);

        $em->persist($panierObj);
        $em->flush();

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
                'pourcentage'=> $pourc
            ));
        }

        # pass selected products to the twig page to show them
        return $this->render('panier/mycart.html.twig');


    }

}
