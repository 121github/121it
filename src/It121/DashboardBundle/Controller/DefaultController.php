<?php

namespace It121\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	
	private function getProjectsForMenu () {
		$em = $this->getDoctrine()->getManager();
		
		$projects = $em->getRepository('ProjectBundle:Project')->findAllWithServer();
		
		$aux = array();
		foreach ($projects as $project) {
			if ($project->getServer()) {
				$aux[$project->getServer()->getSubtype()->getName()][$project->getId()] = $project;
			}
		}
		$projects = $aux;
		
		return $projects;
	}
	
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	//Get the servers
    	$servers = $em->getRepository('ServerBundle:Server')->findBy(array(), array('type' => 'ASC'));
    	
    	//Get the projects for the menu
    	$projectsMenu = $this->getProjectsForMenu();
    	
    	return $this->render('DashboardBundle:Default:index.html.twig', array(
        	'servers' => $servers,
    		'projectsMenu' => $projectsMenu,
        ));
    }
}
