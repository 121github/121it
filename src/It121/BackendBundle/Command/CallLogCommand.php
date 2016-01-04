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
		->setDescription('Check the call logs')
		->addOption('all', null, InputOption::VALUE_NONE, 'If set, check all the files')
		->addOption('date', null, InputOption::VALUE_REQUIRED, 'If set, check the files in the specific date (Ymd)')
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

		$startDate = new \DateTime('now');

		$pathLogs16 = ($this->getContainer()->getParameter('call_logs_16'));
		$tmpDir16 = $tmpDir ='docs/tmp/log/call/unit16';
		$pathLogs31 = ($this->getContainer()->getParameter('call_logs_31'));
		$tmpDir31 = 			$tmpDir ='docs/tmp/log/call/unit31';

		//Check if exist any tmp file (the process is still running)
		$filesTmp16 = glob($tmpDir.'/*.txt');
		$filesTmp31 = glob($tmpDir.'/*.txt');
		if (empty($filesTmp16) || empty($filesTmp31)) {
			if ($input->getOption('all')) {
				$output->writeln("Checking all the files");
				//Check the calls on Unit 16
				$output->writeln("");
				$output->write("  UNIT 16: ");
				$output->writeln("");
				$this->checkLogFiles($output, 16, $pathLogs16, $tmpDir16);

				//Check the calls on Unit 31
				$output->writeln("");
				$output->write("  UNIT 31: ");
				$output->writeln("");
				$this->checkLogFiles($output, 31, $pathLogs31, $tmpDir31);

			}
			else if ($input->getOption('date')) {
				$date = ($input->getOption('date'));
				$output->writeln("Checking only the ".$date." log file...");

				//Check the calls on Unit 16
				$output->writeln("");
				$output->write("  UNIT 16: ");
				$output->writeln("");
				$this->checkLogFiles($output, 16, $pathLogs16, $tmpDir16, $date);



				//Check the calls on Unit 31
				$output->writeln("");
				$output->write("  UNIT 31: ");
				$output->writeln("");
				$this->checkLogFiles($output, 31, $pathLogs31, $tmpDir31, $date);
			}
			else {
				$output->writeln("Checking only the today log file...");

				//Check the calls on Unit 16
				$output->writeln("");
				$output->write("  UNIT 16: ");
				$output->writeln("");
				$this->checkLogFiles($output, 16, $pathLogs16, $tmpDir16, date("Ymd"));



				//Check the calls on Unit 31
				$output->writeln("");
				$output->write("  UNIT 31: ");
				$output->writeln("");
				$this->checkLogFiles($output, 31, $pathLogs31, $tmpDir31, date("Ymd"));
			}
		}
		else {
			$output->writeln("The process is still running");
			throw new \ErrorException('The process is still running');
		}

		$endDate = new \DateTime('now');
		$totalTime = $startDate->diff($endDate);

		$output->writeln("");
		
		$output->write("Call log checking End");
		$output->writeln(" (Finished in ".$totalTime->format('%H:%M:%S').") ");
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
			$numInserted = $this->check($csv, $file_name, $unit, $output);

			$endDate = new \DateTime('now');
			$totalTime = $startDate->diff($endDate);
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
	private function check($file, $file_name, $unit, $output) {
		$em = $this->getContainer()->get('doctrine')->getManager();
		$em121sys = $this->getContainer()->get('doctrine')->getManager('121sys');

		$callLogFile = $em->getRepository('LogBundle:CallLogFile')->findOneBy(
			array('name' => $file_name)
		);

		if (!$callLogFile) {
			$callLogFile = new CallLogFile();
			$callLogFile->setName($file_name);
			$fileDate = substr($file_name, strrpos($file_name,".txt")-8,8);
			$callLogFile->setFileDate(new \DateTime($fileDate));
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

		//Get the calls already inserted
		$callsInserted = array();
		foreach($callLog as $call) {
			array_push($callsInserted,$call->getCallDate()->format("Y/m/d H:i:s")."_".$call->getCallFrom());
		}
		$aux = array();
		foreach($file as $data) {
			//Keep to insert the new calls that are not inserted yet.
            if (!isset($data[0]) ||
                !isset($data[1]) ||
                !isset($data[2]) ||
                !isset($data[3]) ||
                !isset($data[4]) ||
                !isset($data[5]) ||
                !isset($data[6]) ||
                !isset($data[7]) ||
                !isset($data[8]) ||
                !isset($data[9]) ||
                !isset($data[10]) ||
                !isset($data[11]) ||
                !isset($data[12]) ||
                !isset($data[13]) ||
                !isset($data[14]) ||
                !isset($data[15]) ||
                !isset($data[16]) ||
                !isset($data[17]) ||
                !isset($data[18]) ||
                !isset($data[19]) ||
                !isset($data[20]) ||
                !isset($data[21]) ||
                !isset($data[22]) ||
                !isset($data[23]) ||
                !isset($data[24]) ||
                !isset($data[25]) ||
                !isset($data[26]) ||
                !isset($data[27]) ||
                !isset($data[28])
            ) {
                $output->writeln("");
                $output->write("\t\t\t   - Filed line -> ");
                foreach($data as $col) {
                    $output->write($col." | ");
                }

                //Write the failed lines into the logerr
            }
			else if (!in_array($data[0]."_".$data[3],$callsInserted)) {
				array_push($aux, $data);
			}
		}
		$file = $aux;

		//Process the data
		foreach($file as $data) {

			$callLog = new CallLog();
			$callDate = new \DateTime(str_replace("/","-",$data[0]));

			$callLog->setCallDate($callDate);

			//Reformat the duration if it is necessary
			$hours = (intval(substr($data[1],0,2))<=24?substr($data[1],0,2):'08');
			$minutes = (intval(substr($data[1],3,2))<=60?substr($data[1],3,2):'00');
			$seconds = (intval(substr($data[1],6,2))<=60?substr($data[1],6,2):'00');

			$callLog->setDuration(new \DateTime($hours.":".$minutes.":".$seconds));
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

			//Check if the campaign id is defined
			$position = strpos($callLog121Sys->getCallTo(),'#');
			if ($position) {
				$campaign_id = substr($callLog121Sys->getCallTo(), $position+1);
				$callLog121Sys->setCampaignId($campaign_id);
			}

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
