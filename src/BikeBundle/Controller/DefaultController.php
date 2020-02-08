<?php

namespace BikeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAdminAction()
    {
        return $this->render('@Bike/Admin/index.html.twig');
    }

    public function indexAction () {
        return $this->render('@Bike/Default/index.html.twig');
    }
}
