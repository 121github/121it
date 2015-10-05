<?php

namespace It121\BackendBundle\Command;

use Ddeboer\DataImport\Filter\OffsetFilter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Ddeboer\DataImport\Writer\ConsoleTableWriter;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class AddressCommand extends ContainerAwareCommand {
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::configure()
	 */
	protected function configure() {
		$this
		->setName('address:import_paf')
		->setDescription('Import addresses from PAF')
		->addOption('file', null, InputOption::VALUE_REQUIRED, 'If set, check the file path')
		;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Console\Command\Command::execute()
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		
		$output->writeln("Starting PAF address import...");
		$output->writeln("");

		$nowDate = new \DateTime('now');

		if ($input->getOption('file')) {
			$filePath = ($input->getOption('file'));
			$output->writeln("Importing from ".$filePath." ...");

            //Truncate data from the file
            $output->writeln("");
            $this->truncatePafPostcode($output);

			//Import data from the file
			$output->writeln("");
			$this->importPafFile($output, $filePath);
		}
		else {
			$output->writeln("You need to specify the complete file path");
			throw new \ErrorException('You need to specify the complete file path');
		}

		$output->writeln("");
		
		$output->write("PAF address import end");
		$output->writeln("");
	}

    /**
     * Truncate table
     */
    private function truncatePafPostcode($output) {
        $output->write("Truncating the data...");

        $entityManager = $this->getContainer()->get('doctrine')->getManager('uk_postcodes');

        $cmdPostcodeIo = $entityManager->getClassMetadata("It121\AddressBundle\Entity\PafPostcode");
        $cmdPafPostcode = $entityManager->getClassMetadata("It121\AddressBundle\Entity\PostcodeIo");
        $connection = $entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($cmdPostcodeIo->getTableName());
            $connection->executeUpdate($q);
            $q = $dbPlatform->getTruncateTableSql($cmdPafPostcode->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();

            $output->writeln(" OK");
        }
        catch (\Exception $e) {
            $connection->rollback();
            $output->writeln(" ERROR");
        }
    }

	/**
	 * Get all the log files
	 */
	private function importPafFile($output, $pathFile) {
		$entityManager = $this->getContainer()->get('doctrine')->getManager('uk_postcodes');

		$output->write("Reading the file...");

		// Create and configure the reader
		$file = new \SplFileObject($pathFile);
		$csvReader = new CsvReader($file, ',');

		// Tell the reader that the first row in the CSV file contains column headers
		//$csvReader->setHeaderRowNumber(0, CsvReader::DUPLICATE_HEADERS_INCREMENT);

		$csvReader->setColumnHeaders(array(
			'postcode',
			'postTown',
			'dependentLocality',
			'doubleDependentLocality',
			'thoroughfareAndDescriptor',
			'dependentThoroughfareAndDescriptor',
			'buildingNumber',
			'buildingName',
			'subBuildingName',
			'poBox',
			'departmentName',
			'organisationName',
			'udprn',
			'postcodeType',
			'suOrganisationIndicator',
			'deliveryPointSuffix'
		));

        $output->writeln(" OK");

		// Create the workflow from the reader
		$workflow = new Workflow($csvReader);

		// Create a writer: you need Doctrine’s EntityManager.
		$doctrineWriter = new DoctrineWriter($entityManager, 'AddressBundle:PafPostcode');
		$doctrineWriter->disableTruncate();
		$workflow->addWriter($doctrineWriter);

        //ConsoleProgressWriter
        $output = new ConsoleOutput();
        $progressWriter = new ConsoleProgressWriter($output, $csvReader, 'normal', 1);
		$workflow->addWriter($progressWriter);

        //ConsoleTableWriter (just for debug)
        $table = new Table($output);
        // Make some manipulations, e.g. set table style
        //$table->setStyle('compact');
        $tableWriter = new ConsoleTableWriter($output, $table);
		//$workflow->addWriter($tableWriter);

        $output->writeln("Importing into database...");
		// Process the workflow
		$result = $workflow->process();
	}
}
