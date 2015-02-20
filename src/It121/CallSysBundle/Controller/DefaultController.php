<?php

namespace It121\CallSysBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CallSysBundle:Default:index.html.twig', array('name' => $name));
    }
}
