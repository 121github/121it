<?php

namespace It121\ServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ServerBundle:Default:index.html.twig', array('name' => $name));
    }
}
