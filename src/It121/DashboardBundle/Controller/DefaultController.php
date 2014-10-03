<?php

namespace It121\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
	
	private function getProjectsForMenu ($projects) {
		$em = $this->getDoctrine()->getManager();
		
		$aux = array();
		foreach ($projects as $project) {
			$aux[$project->getGroup()->getName()][$project->getId()] = $project;
		}
		$projects = $aux;
		
		return $projects;
	}
	
	private function getEnviromentsForMenu ($projects) {
		$em = $this->getDoctrine()->getManager();
	
		$aux = array();
		foreach ($projects as $project) {
			$aux[$project->getGroup()->getName()][$project->getId()] = $project;
		}
		$projects = $aux;
	
		return $projects;
	}
	
	private function getRss($url, $username, $password) {

		$context = stream_context_create(array('http' => array('header'  => "Authorization: Basic " . base64_encode($username . ':' . $password))));
		
		$feed = file_get_contents($url, false, $context);
		$xml = simplexml_load_string($feed);
		
		$result = array();
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
		
		return $result;
	}
	
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	//Get the servers
    	$servers = $em->getRepository('ServerBundle:Server')->findBy(array(), array('type' => 'ASC'));
    	
    	$projects = $em->getRepository('ProjectBundle:Project')->findAll();
    	//Get the projects for the menu
    	$projectsMenu = $this->getProjectsForMenu($projects);
    	
    	//Get Rss for the Deployment in Jenkins
    	$url = 'http://www.121leads.co.uk:8080/rssLatest';
    	$username = 'estebanc';
    	$password = 'esteban123P';
    	$deployment = $this->getRss($url, $username, $password);
    	
    	//Get Environments
    	$environments = $em->getRepository('ServerBundle:ServerEnvironment')->findAll();
    	
    	return $this->render('DashboardBundle:Default:index.html.twig', array(
        	'servers' => $servers,
    		'projectsMenu' => $projectsMenu,
    		'deployment' => $deployment,
    		'environments' => $environments,
        ));
    }
}
