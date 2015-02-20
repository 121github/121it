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
	 * Check method (common) for each file
	 */
	private function check($file, $file_name, $unit) {
		$em = $this->getContainer()->get('doctrine')->getManager();
		$em121sys = $this->getContainer()->get('doctrine')->getManager('121sys');

		$callLogFile = $em->getRepository('LogBundle:CallLogFile')->findOneBy(
			array('name' => $file_name)
		);

		if (!$callLogFile) {
			$callLogFile = new CallLogFile();
			$callLogFile->setName($file_name);
			$callLogFile->setFileDate(new \DateTime(substr($file_name, strrpos($file_name,".txt")-8,8)));
			$callLogFile->setUnit($unit);

			Util::setCreateAuditFields($callLogFile, 1);

			//Save the callLogFile in the 121sys database
			$callLogFile121sys = new \It121\CallSysBundle\Entity\CallLogFile();
			$callLogFile121sys->setFileDate($callLogFile->getFileDate());
			$callLogFile121sys->setName($callLogFile->getName());
			$callLogFile121sys->setUnit($callLogFile->getUnit());
			$em121sys->persist($callLogFile121sys);
		}
		else {
			$callLogFile121sys = $em121sys->getRepository('CallSysBundle:CallLogFile')->findOneBy(
				array('name' => $file_name)
			);
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
			}
			if (!in_array($data[0]."_".$data[3],$callsInserted)) {
				array_push($aux, $data);
			}
		}
		$file = $aux;

		//Process the data
		foreach($file as $data) {

			$callLog = new CallLog();
			$callDate = new \DateTime(str_replace("/","-",$data[0]));

			$callLog->setCallDate($callDate);
			$callLog->setDuration(new \DateTime($data[1]));
			$callLog->setRingTime($data[2]);
			$callLog->setCallFrom($data[3]);
			$callLog->setInbound(($data[4]=="I"?1:0));
			$callLog->setCallToExt(($data[4]=="I"?$data[5]:''));
			$callLog->setCallTo($data[6]);
			$callLog->setColH($data[7]);
			$callLog->setColI($data[8]);
			$callLog->setCallId($data[9]);
			$callLog->setColK($data[10]);
			$callLog->setRefFrom($data[11]);
			$callLog->setNameFrom($data[12]);
			$callLog->setRefTo($data[13]);
			$callLog->setNameTo($data[14]);
			$callLog->setColP($data[15]);
			$callLog->setColQ($data[16]);
			$callLog->setColR($data[17]);
			$callLog->setColS($data[18]);
			$callLog->setColT($data[19]);
			$callLog->setColU($data[20]);
			$callLog->setColV($data[21]);
			$callLog->setColW($data[22]);
			$callLog->setColX($data[23]);
			$callLog->setColY($data[24]);
			$callLog->setColZ($data[25]);
			$callLog->setColAA($data[26]);
			$callLog->setColAB($data[27]);
			$callLog->setColAC($data[28]);
			$callLog->setFile($callLogFile);

			Util::setCreateAuditFields($callLog, 1);
			Util::setModifyAuditFields($callLog, 1);

			$em->persist($callLog);

			//Persist the data in the 121sys database
			$callLog121Sys = new \It121\CallSysBundle\Entity\CallLog();
			$callLog121Sys->setCallDate($callLog->getCallDate());
			$callLog121Sys->setDuration($callLog->getDuration());
			$callLog121Sys->setRingTime($callLog->getRingTime());
			$callLog121Sys->setCallFrom($callLog->getCallFrom());
			$callLog121Sys->setInbound($callLog->getInbound());
			$callLog121Sys->setCallToExt($callLog->getCallToExt());
			$callLog121Sys->setCallTo($callLog->getCallTo());
			$callLog121Sys->setCallId($callLog->getCallId());
			$callLog121Sys->setRefFrom($callLog->getRefFrom());
			$callLog121Sys->setNameFrom($callLog->getNameFrom());
			$callLog121Sys->setRefTo($callLog->getRefTo());
			$callLog121Sys->setNameTo($callLog->getNameTo());
			$callLog121Sys->setFile($callLogFile121sys);

			$em121sys->persist($callLog121Sys);
		}
		$em->flush();
		$em121sys->flush();
		
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
