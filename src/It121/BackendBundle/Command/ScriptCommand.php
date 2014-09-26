<?php

namespace It121\BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use It121\BackendBundle\Util\Util;
use It121\ServerBundle\Entity\Server;

class ScriptCommand extends ContainerAwareCommand {
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::configure()
	 */
	protected function configure() {
		$this
		->setName('servers:check')
		->setDescription('Check the server status')
		//->addArgument('type', InputArgument::OPTIONAL, 'What do you want to check?')
		->addOption('all', null, InputOption::VALUE_NONE, 'If set, check the websites, services and servers')
		->addOption('websites', null, InputOption::VALUE_NONE, 'If set, only check the websites')
		->addOption('services', null, InputOption::VALUE_NONE, 'If set, only check the services')
		->addOption('servers', null, InputOption::VALUE_NONE, 'If set, only check the servers')
		;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::execute()
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$output->writeln("Starting servers checking...");
		$output->writeln("");
		
		//$type = $input->getArgument('type');
		$entityManager = $this->getContainer()->get('doctrine')->getManager();
		
		if ($input->getOption('all')) {
			$output->writeln("Checking all...");
			//Get the servers
			$serverList = $entityManager->getRepository('ServerBundle:Server')->findAll();
		}			
		elseif ($input->getOption('websites')) {
			$output->writeln("Checking only the websites...");
			//Get the websites
			$serverList = $entityManager->getRepository('ServerBundle:Server')->findWebsites();
		}
		elseif ($input->getOption('services')) {
			$output->writeln("Checking only the services...");
			//Get the services
			$serverList = $entityManager->getRepository('ServerBundle:Server')->findServices();
		}
		elseif ($input->getOption('servers')) {
			$output->writeln("Checking only the servers...");
			//Get the servers
			$serverList = $entityManager->getRepository('ServerBundle:Server')->findServers();
		}
		else {
			$output->writeln("Checking all...");
			//Get the servers
			$serverList = $entityManager->getRepository('ServerBundle:Server')->findAll();
		}
		$output->writeln("");

		foreach ($serverList as $server) {
			$output->write("> ".$server->getUrl()." ......");
			
			$success = $this->check($server);
			
			$output->writeln($success);
			$output->writeln("");
		}
		
		$output->writeln("Server checking End");
	}
	
	
	/**
	 * Check method (common)
	 */
	private function check(Server $server) {
		$entityManager = $this->getContainer()->get('doctrine')->getManager();
		
		if ($server->getMonitoring()) {
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $server->getUrl());
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
			$starttime=microtime(true);
			$response=curl_exec($ch);
			$endtme=microtime(true);
			$latency=$endtme-$starttime;
			
			if ($response) {
				$server->setLatency($latency);
				$server->setStatus($entityManager->getRepository('ServerBundle:ServerStatus')->findOneBy(array(
						'name' => 'Ok'
				)));
				$server->setLastOnline(new \DateTime('now'));
					
				$success = "OK";
			}
			else {
				$server->setLatency($latency);
				$server->setStatus($entityManager->getRepository('ServerBundle:ServerStatus')->findOneBy(array(
						'name' => 'Error'
				)));
					
				$success = "ERROR";
			}
				
			$server->setLastCheck(new \DateTime('now'));
		}
		else {
			$server->setStatus($entityManager->getRepository('ServerBundle:ServerStatus')->findOneBy(array(
						'name' => 'Warning'
				)));
			
			$success = "Monitoring => false";
		}
		
		Util::setModifyAuditFields($server, 1);
			
		$entityManager->persist($server);
		$entityManager->flush();
		
		return $success;
	}
	
}