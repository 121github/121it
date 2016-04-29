<?php

namespace It121\CronjobsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CronjobsBundle:Default:index.html.twig', array('name' => $name));
    }
}
