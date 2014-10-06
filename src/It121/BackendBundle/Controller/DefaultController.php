<?php

namespace It121\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	private function getProjectsForMenu ($projects) {
		$aux = array();
		foreach ($projects as $project) {
			$aux[$project->getGroup()->getName()][$project->getId()] = $project;
		}
		$projects = $aux;
	
		return $projects;
	}
	
	protected function getElementsForMenu() {
		$em = $this->getDoctrine()->getManager();
		
		//Get the projects for the menu
		$projectsMenu = $this->getProjectsForMenu($em->getRepository('ProjectBundle:Project')->findAll('Development'));
		 
		//Get the projects for the menu
		$managementProjectsMenu = $this->getProjectsForMenu($em->getRepository('ProjectBundle:Project')->findAll('Management'));
		
		//Get Environments for the menu
		$environmentsMenu = $em->getRepository('ServerBundle:ServerEnvironment')->findAll();
		
		//Get the services for the menu
		$servicesMenu = $em->getRepository('ServerBundle:Server')->findServersByType('Service');
		
		return array(
			'projectsMenu' => $projectsMenu,
			'managementProjectsMenu' => $managementProjectsMenu,
			'environmentsMenu' => $environmentsMenu,
			'servicesMenu' => $servicesMenu,
		);
	}
	
    public function indexAction()
    {
        return $this->render('BackendBundle:Default:index.html.twig', array());
    }
}
