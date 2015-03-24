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

class DeleteCallLogCommand extends ContainerAwareCommand {
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::configure()
	 */
	protected function configure() {
		$this
		->setName('log:calls:delete')
		->setDescription('Delete the old call logs')
		->addOption('database', null, InputOption::VALUE_REQUIRED, 'Which database do you want to check?')
        ->addOption('days', null, InputOption::VALUE_REQUIRED, 'How many days do you want to maintain in the database?')
		;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::execute()
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {

        $output->writeln("");
		$output->writeln("<question>Starting call log checking...</question>");
		$output->writeln("");

		$database = ($input->getOption('database'));
        $days = ($input->getOption('days'));

        if (!$database || ($database != '121sys' && $database!='121it')) {
            throw new \ErrorException('The database selected does not exist!! Select a database (121sys, 121it)');
        }
        else {
            if (!$days) {
                throw new \ErrorException("Please select the days do you want to maintain in the database");
            }
            else if(!is_numeric($days)) {
                throw new \ErrorException("The option 'days' should be a number");
            }
            else if ($days < 30) {
                throw new \ErrorException("The option 'days' should be greater than 30");
            }
            else {
                $date = (new \DateTime('now - '.$days.' days'))->format("Y-m-d");
                $output->write("<comment>\t Deleting the call logs from the ".$database." database older than ".($days)." days (".$date.")...</comment>");

                $num_del = $this->deleteCallLog($database, $date);
                $output->writeln(($num_del>0?"<info>":"<error>")." ".$num_del." call logs deleted".($num_del>0?"</info>":"</error>"));

            }
        }


		$output->writeln("");
        $output->writeln("<info>Delete call log process End</info>");
        $output->writeln("");
	}

    /**
     * Delete the old call logs
     */
    private function deleteCallLog($database, $date) {

        if ($database == "121sys") {
            $em121sys = $this->getContainer()->get('doctrine')->getManager('121sys');
            $result = $em121sys->getRepository('CallSysBundle:CallLog')->deleteOldCallLogs($date);
        }
        else if ($database == "121it") {
            $em = $this->getContainer()->get('doctrine')->getManager();
            $result = $em->getRepository('LogBundle:CallLog')->deleteOldCallLogs($date);
        }

        return $result;

    }
	
}
