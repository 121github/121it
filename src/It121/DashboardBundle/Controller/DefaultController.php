<?php

namespace It121\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	//Get the servers
    	$servers = $em->getRepository('ServerBundle:Server')->findBy(array(), array('type' => 'ASC'));
    	
    	return $this->render('DashboardBundle:Default:index.html.twig', array(
        	'servers' => $servers,
        ));
    }
}
