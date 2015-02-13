<?php

namespace It121\DashboardBundle\Controller;

use It121\BackendBundle\Command\StatusProjectCommand;
use It121\BackendBundle\Command\StatusServerCommand;
use JMS\Serializer\SerializerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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
				'link' =>  substr($xml->entry[$i]->link['href'],0),
				'published' => substr($xml->entry[$i]->published,0),
				'updated' => substr($xml->entry[$i]->updated,0),
				));
			}	
		}
		
		return $result;
	}
	
    public function indexAction()
    {
    	$options = array();

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

	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  Check Server ACTION ************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/

	/**
	 * Check server view
	 *
	 */
	public function checkServerAction()
	{

		$em = $this->getDoctrine()->getManager();

		//Run the servers check command
		$command = new StatusServerCommand();
		$command->setContainer($this->container);
		$input = new ArrayInput(array());
		$output = new NullOutput();
		$resultCode = $command->run($input, $output);

		//Get the servers
		$servers = $em->getRepository('ServerBundle:Server')->findBy(array(), array('type' => 'ASC'));

		$servers = array(
			"success" => (!empty($servers)),
			"data" => $servers
		);

		$serializer = SerializerBuilder::create()->build();
		$servers = $serializer->serialize($servers, 'json');

		$response = new Response($servers);

		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}


	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  Check Deployment ACTION ********************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/

	/**
	 * Check Deployment view
	 *
	 */
	public function checkDeploymentAction()
	{

		$em = $this->getDoctrine()->getManager();

		//Run the servers check command
		$command = new StatusProjectCommand();
		$command->setContainer($this->container);
		$input = new ArrayInput(array());
		$output = new NullOutput();
		$resultCode = $command->run($input, $output);

		//Get Rss for the Deployment in Jenkins
		$url = 'http://www.121leads.co.uk:8080/view/All/rssLatest';
		$username = 'estebanc';
		$password = 'esteban123P83';
		$deployment = $this->getRss($url, $username, $password);

		$serializer = SerializerBuilder::create()->build();

		$deployment = array(
			"success" => (!empty($deployment)),
			"data" => $deployment
		);

		$deployment = $serializer->serialize($deployment, 'json');

		$response = new Response($deployment);

		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}
}


