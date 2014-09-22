<?php

namespace It121\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DashboardBundle:Default:index.html.twig', array());
    }
}
