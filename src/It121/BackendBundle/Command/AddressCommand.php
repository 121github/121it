<?php

namespace It121\BackendBundle\Command;

use Ddeboer\DataImport\Filter\OffsetFilter;
use Ddeboer\DataImport\ItemConverter\MappingItemConverter;
use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Ddeboer\DataImport\Writer\ConsoleTableWriter;
use Ddeboer\DataImport\Writer\DoctrineWriter;
use It121\AddressBundle\Entity\PostcodeIo;
use Proxies\__CG__\It121\AddressBundle\Entity\PafPostcode;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
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

			//Set postcodeIo
			$output->writeln("");
			$this->setPostcodeIo($output);
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

    /**
     * Set postcodesIo
     */
    private function setPostcodeIo($output)
    {
		$output->writeln("");
		$output->writeln("Get the postcodeIo details...");

		//Get the PafPostcodes
        $entityManager = $this->getContainer()->get('doctrine')->getManager('uk_postcodes');
		$pafPostcodes = $entityManager->getRepository('It121\AddressBundle\Entity\PafPostcode')->findAll();

        // create a new progress bar (50 units)
        $progress = new ProgressBar($output, 1);

        // start and displays the progress bar
        $progress->start();

		$client = $this->getContainer()->get('box_uk_postcodes_io.client');

		$i = 0;
		foreach($pafPostcodes as $pafPostcode) {
            $postcodeIo = $entityManager->getRepository('AddressBundle:PostcodeIo')->findBy(array('postcode' => $pafPostcode->getPostcode()));

            if (!$postcodeIo) {
                $postcodeIo = new PostcodeIo();

                try{
                    $response = $client->lookup(array('postcode' => $pafPostcode->getPostcode()));

                    $postcodeIo->setPostcode($response['result']['postcode']);
                    $postcodeIo->setQuality($response['result']['quality']);
                    $postcodeIo->setEastings($response['result']['eastings']);
                    $postcodeIo->setNorthings($response['result']['northings']);
                    $postcodeIo->setCountry($response['result']['country']);
                    $postcodeIo->setNhsHa($response['result']['nhs_ha']);
                    $postcodeIo->setLongitude($response['result']['longitude']);
                    $postcodeIo->setLatitude($response['result']['latitude']);
                    $postcodeIo->setParliamentaryConstituency($response['result']['parliamentary_constituency']);
                    $postcodeIo->setEuropeanElectoralRegion($response['result']['european_electoral_region']);
                    $postcodeIo->setPrimaryCareTrust($response['result']['primary_care_trust']);
                    $postcodeIo->setRegion($response['result']['region']);
                    $postcodeIo->setLsoa($response['result']['lsoa']);
                    $postcodeIo->setMsoa($response['result']['msoa']);
                    $postcodeIo->setIncode($response['result']['incode']);
                    $postcodeIo->setOutcode($response['result']['outcode']);
                    $postcodeIo->setAdminDistrict($response['result']['admin_district']);
                    $postcodeIo->setParish($response['result']['parish']);
                    $postcodeIo->setAdminCounty($response['result']['admin_county']);
                    $postcodeIo->setAdminWard($response['result']['admin_ward']);
                    $postcodeIo->setCcg($response['result']['ccg']);
                    $postcodeIo->setNuts($response['result']['nuts']);

                    $entityManager->persist($postcodeIo);

                    // advance the progress bar 1 unit
                    $progress->advance();
                    $i++;

                } catch(\Exception $e){

                }
            }

            $pafPostcode->setPostcodeIo($postcodeIo);

            $entityManager->persist($pafPostcode);
		}

		$entityManager->flush();

        // ensure that the progress bar is at 100%
        $progress->finish();

		$output->writeln("");

    }
}
