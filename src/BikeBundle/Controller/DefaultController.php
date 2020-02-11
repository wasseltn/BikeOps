<?php

namespace BikeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAdminAction()
    {
        return $this->render('base.html.twig');
    }

    public function indexAction () {
        return $this->render('home.html.twig');
    }

    public function signUpAction () {
        return $this->render('signup.html.twig');
    }

    public function loginAction () {
        return $this->render('login.html.twig');
    }
}
