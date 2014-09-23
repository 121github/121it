<?php

namespace It121\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ProjectBundle:Default:index.html.twig', array('name' => $name));
    }
}
