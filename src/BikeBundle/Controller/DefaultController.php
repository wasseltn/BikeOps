<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAdminAction()
    {
        return $this->render('admin_home.html.twig');
    }

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository(Produit::class)->findAll();
        return $this->render('home.html.twig', array(
            'produits' => $produits,
        ));
    }

    public function signUpAction()
    {
        return $this->render('signup.html.twig');
    }

    public function loginAction()
    {
        return $this->render('login.html.twig');

    }

    public function myprofileAction()
    {
        return $this->render('profile/profile.html.twig');
    }

    public function myCartAction()
    {
        return $this->render('panier/mycart.html.twig');
    }

    public function loginAdminAction()
    {
        return $this->render('login_admin.html.twig');
    }


}
