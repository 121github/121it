<?php

namespace It121\AddressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AddressBundle:Default:index.html.twig', array('name' => $name));
    }
}
