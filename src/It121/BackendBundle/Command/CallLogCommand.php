<?php

namespace It121\BackendBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use It121\BackendBundle\Util\Util;
use It121\LogBundle\Entity\CallLog;
use It121\LogBundle\Entity\CallLogFile;
use Symfony\Component\Filesystem\Filesystem;

class CallLogCommand extends ContainerAwareCommand {
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::configure()
	 */
	protected function configure() {
		$this
		->setName('log:calls:check')
		->setDescription('Check the server status')
		->addOption('all', null, InputOption::VALUE_NONE, 'If set, check all the files')
		;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::execute()
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$output->writeln("Starting call log checking...");
		$output->writeln("");

		$entityManager = $this->getContainer()->get('doctrine')->getManager();
		
		if ($input->getOption('all')) {
			$output->writeln("Checking all the files");
			//Check the calls on Unit 16
			$pathLogs = ($this->getContainer()->getParameter('call_logs_16'));
			$tmpDir = 			$tmpDir ='docs/tmp/log/call/unit16';
			$logFiles = $this->checkLogFiles($output, 16, $pathLogs, $tmpDir);
		}
		else {
			$output->writeln("Checking only the today log file...");

			//Check the calls on Unit 16
			$output->writeln("");
			$output->write("  UNIT 16: ");
			$output->writeln("");
			$pathLogs = ($this->getContainer()->getParameter('call_logs_16'));
			$tmpDir = 			$tmpDir ='docs/tmp/log/call/unit16';
			$this->checkLogFiles($output, 16, $pathLogs, $tmpDir, date("Ymd"));

			//Check the calls on Unit 31
			$output->writeln("");
			$output->write("  UNIT 31: ");
			$output->writeln("");
			$pathLogs = ($this->getContainer()->getParameter('call_logs_31'));
			$tmpDir = 			$tmpDir ='docs/tmp/log/call/unit31';
			$this->checkLogFiles($output, 31, $pathLogs, $tmpDir, date("Ymd"));
		}
		$output->writeln("");
		
		$output->writeln("Call log checking End");
	}

	/**
	 * Get all the log files
	 */
	private function checkLogFiles($output, $unit, $pathLogs, $tmpDir, $date = null) {
		$fs = new Filesystem();


		//Copy the files from unit 16 to the tmp folder into the project
		$files = glob($pathLogs.'/*'.$date.'*.txt');
		$output->write("\t - Copying the log files to a tmp dir... ");
		$output->writeln("");
		foreach($files as $file) {
			//If exist will be overridden
			$file_name = substr($file, strrpos($file,"/")+1);
			$output->write("\t\t ".$file." > ".$tmpDir."/".$file_name." ...");
			$fs->copy($file,$tmpDir."/".$file_name,true);
			$output->write(" OK");
			$output->writeln("");
		}
		$files = glob($tmpDir.'/*'.$date.'*.txt');
		$output->writeln("");
		$output->write("\t\t # ".count($files)." copied successfully!");
		$output->writeln("");
		$output->writeln("");
		$output->write("\t - Processing the files... ");
		$output->writeln("");
		foreach($files as $file) {
			$startDate = new \DateTime('now');
			$output->write("\t\t - FILE: ".$file.".....");
			//Process the file
			$file_name = substr($file, strrpos($file,"/")+1);
			$csv = $this->parseCSV($file);
			$numInserted = $this->check($csv, $file_name, $unit);

			$endDate = new \DateTime('now');
			$totalTime = $startDate->diff($endDate);
			$output->write(" OK. ");
			$output->writeln("");
			$output->write("\t\t\t # Finished in ".$totalTime->format('%H:%M:%S').". ");
			$output->writeln("");
			$output->write("\t\t\t # ".$numInserted." new calls inserted. ");
			$output->writeln("");

			//Remove the tmp file
			$fs->remove($file);
			$output->write("\t\t\t # File removed from the tmp dir.");
			$output->writeln("");
		}
		$output->writeln("");
	}
	
	
	/**
	 * Check method (common)
	 */
	private function check($file, $file_name, $unit) {
		$em = $this->getContainer()->get('doctrine')->getManager();

		$callLogFile = $em->getRepository('LogBundle:CallLogFile')->findOneBy(
			array('name' => $file_name)
		);

		if (!$callLogFile) {
			$callLogFile = new CallLogFile();
			$callLogFile->setName($file_name);
			$callLogFile->setFileDate(new \DateTime(substr($file_name, strrpos($file_name,".txt")-8,8)));
			$callLogFile->setUnit($unit);

			Util::setCreateAuditFields($callLogFile, 1);
		}

		Util::setModifyAuditFields($callLogFile, 1);

		$em->persist($callLogFile);


		$callLog = $em->getRepository('LogBundle:CallLog')->findBy(
			array(
				'file' => $callLogFile->getId()
			)
		);

		$callsInserted = array();
		foreach($callLog as $call) {
			array_push($callsInserted,$call->getCallDate()->format("Y/m/d H:i:s")."_".$call->getCallFrom());
		}
		$aux = array();
		foreach($file as $data) {
			if (!isset($data[3])) {
				var_dump($data);
			}
			if (!in_array($data[0]."_".$data[3],$callsInserted)) {
				array_push($aux, $data);
			}
		}
		$file = $aux;


		//Process the data
		foreach($file as $data) {

			$callDate = new \DateTime(str_replace("/","-",$data[0]));
			$inbound = ($data[4]=="I"?1:0);

			$callLog = new CallLog();
			$callLog->setCallDate($callDate);
			$callLog->setDuration(new \DateTime($data[1]));
			$callLog->setCallFrom($data[3]);
			$callLog->setInbound($inbound);
			$callLog->setCallTo(($inbound?$data[6]."-".$data[5]:$data[6]));
			$callLog->setName($data[12]);
			$callLog->setFile($callLogFile);

			Util::setCreateAuditFields($callLog, 1);
			Util::setModifyAuditFields($callLog, 1);

			$em->persist($callLog);
		}
		$em->flush();
		
		return count($file);
	}

	/**
	 * Parse a csv file
	 *
	 * @return array
	 */

	private function parseCSV($file)

	{
		$rows = array();
		if (($handle = fopen($file, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
				$rows[] = $data;
			}
			fclose($handle);
		}
		return $rows;
	}
	
}
