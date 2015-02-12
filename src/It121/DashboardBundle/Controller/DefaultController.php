<?php

namespace It121\DashboardBundle\Controller;

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
	
	private function getRss($url, $username, $password) {

		$result = array();
		
		if ($url) {
			$context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode($username . ':' . $password))));
			
			$feed = file_get_contents($url, false, $context);
			$xml = simplexml_load_string($feed);
			
			for ($i = 0; $i < count($xml->entry); $i++ ) {
				$title = substr($xml->entry[$i]->title, 0, strpos($xml->entry[$i]->title, '#'));
				$version = substr($xml->entry[$i]->title, strpos($xml->entry[$i]->title, '#')+1, strpos($xml->entry[$i]->title, '(')-strpos($xml->entry[$i]->title, '#')-2);
				$status = substr($xml->entry[$i]->title, strpos($xml->entry[$i]->title, '(')+1, strpos($xml->entry[$i]->title, ')')-strpos($xml->entry[$i]->title, '(')-1);
				array_push($result, array(
				'title' => $title,
				'version' => $version,
				'status' => $status,
				'link' =>  $xml->entry[$i]->link['href'],
				'published' => $xml->entry[$i]->published,
				'updated' => $xml->entry[$i]->updated,
				));
			}	
		}
		
		return $result;
	}
	
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	//Get the servers
    	$servers = $em->getRepository('ServerBundle:Server')->findBy(array(), array('type' => 'ASC'));
    	
    	//Get Rss for the Deployment in Jenkins
    	$url = 'http://121leads.co.uk:8080/rssLatest';
    	$username = 'estebanc';
    	$password = 'esteban123P83';
    	$deployment = $this->getRss($url, $username, $password);
    	
    	$options = array(
    		'servers' => $servers,
    		'deployment' => $deployment,
    	);
    	$elementsForMenu = $this->getElementsForMenu();
    	
    	return $this->render('DashboardBundle:Default:index.html.twig', array_merge($options, $elementsForMenu));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /********************************  ENVIRONMENT LIST ACTION ********************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Environment view
     *
     */
    public function environmentAction($name)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	if ($name) {
    		$projects = $em->getRepository('ProjectBundle:Project')->findEnvironment($name);
    	}
    	else {
    		$projects = $em->getRepository('ProjectBundle:Project')->findWithEnvironment();
    	}
    	
    	
    	$options = array(
    			'projects' => $projects,
    			'name' => $name,
    	);
    	$elementsForMenu = $this->getElementsForMenu();
    	
    	return $this->render('DashboardBundle:Default:environment.html.twig', array_merge($options, $elementsForMenu));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW PROJECT ACTION *************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Finds and displays a Project entity.
     *
     */
    public function showProjectAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:Project')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Project entity.');
    	}
    	
    	//Get Rss for the Deployment in Jenkins
    	$url = $entity->getServer()->getRssUrl();
    	$username = 'estebanc';
    	$password = 'esteban123P83';
    	$deployment = $this->getRss($url, $username, $password);
    
    	return $this->render('DashboardBundle:Project:show.html.twig', array(
    			'entity'      => $entity,
    			'deployment'      => $deployment,
    	));
    }
}
