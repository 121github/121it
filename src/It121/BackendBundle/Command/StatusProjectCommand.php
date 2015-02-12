<?php

namespace It121\BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use It121\BackendBundle\Util\Util;
use It121\ProjectBundle\Entity\Project;

class StatusProjectCommand extends ContainerAwareCommand {
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::configure()
	 */
	protected function configure() {
		$this
		->setName('projects:check')
		->setDescription('Check the project status')
		->addOption('all', null, InputOption::VALUE_NONE, 'If set, check all projects')
		->addOption('development', null, InputOption::VALUE_NONE, 'If set, only check the projects in the development environment')
		->addOption('test', null, InputOption::VALUE_NONE, 'If set, only check the projects in the test environment')
		->addOption('acceptance', null, InputOption::VALUE_NONE, 'If set, only check the projects in the acceptance environment')
		->addOption('production', null, InputOption::VALUE_NONE, 'If set, only check the projects in the production environment')
		;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::execute()
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$output->writeln("Starting project checking...");
		$output->writeln("");
		
		//$type = $input->getArgument('type');
		$entityManager = $this->getContainer()->get('doctrine')->getManager();
		
		if ($input->getOption('all')) {
			$output->writeln("Checking all...");
			//Get the projects
			$projectList = $entityManager->getRepository('ProjectBundle:Project')->findWithEnvironment();
		}			
		elseif ($input->getOption('development')) {
			$output->writeln("Checking only the projects in the development environment...");
			//Get the projects in the development environment
			$projectList = $entityManager->getRepository('ProjectBundle:Project')->findEnvironment("Development");
		}
		elseif ($input->getOption('test')) {
			$output->writeln("Checking only the projects in the test environment...");
			//Get the projects in the test environment
			$projectList = $entityManager->getRepository('ProjectBundle:Project')->findEnvironment("Test");
		}
		elseif ($input->getOption('acceptance')) {
			$output->writeln("Checking only the projects in the acceptance environment...");
			//Get the projects in the acceptance environment
			$projectList = $entityManager->getRepository('ProjectBundle:Project')->findEnvironment("Acceptance");
		}
		elseif ($input->getOption('production')) {
			$output->writeln("Checking only the projects in the production environment...");
			//Get the projects in the production environment
			$projectList = $entityManager->getRepository('ProjectBundle:Project')->findEnvironment("Production");
		}
		else {
			$output->writeln("Checking all...");
			//Get the projects
			$projectList = $entityManager->getRepository('ProjectBundle:Project')->findWithEnvironment();
		}
		$output->writeln("");

		foreach ($projectList as $project) {
			$output->write("> ".$project->getName()." ......");
			
			$success = $this->check($project);
			
			$output->writeln($success);
			$output->writeln("");
		}
		
		$output->writeln("Project checking End");
	}
	
	
	/**
	 * Check method (common)
	 */
	private function check(Project $project) {
		$entityManager = $this->getContainer()->get('doctrine')->getManager();
		
		//Get Rss for the Deployment in Jenkins
		$url = $project->getServer()->getRssUrl();
		$username = 'estebanc';
		$password = 'esteban123P83';
		$deployment = $this->getRss($url, $username, $password);
		
		$publishDate = $deployment[0]['published'];
		
		if ($deployment[0]['status'] == 'stable') {
			$status = "Ok";
			$project->setLastDeployment(new \DateTime($publishDate));
			$success = "OK";
		}
		else if (strpos($deployment[0]['status'],'broken') !== false) {
			$status = "Error";
			$project->setLastDeployment(new \DateTime($publishDate));
			$success = "ERROR";
		}
		else if ($deployment[0]['status'] == 'back to normal') {
			$status = "Warning";
			$project->setLastDeployment(new \DateTime($publishDate));
			$success = "WARNING";
		}
		else if (strpos($deployment[0]['status'],'?') !== false) {
			$status = "In Progress";
			$success = "IN PROGRESS";
		}
		
		$project->setStatus($entityManager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array(
				'name' => $status
		)));
		Util::setModifyAuditFields($project, 1);

		$entityManager->persist($project);
		$entityManager->flush();

		return $success;
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
	
}