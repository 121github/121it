<?php
/**
 * Created by IntelliJ IDEA.
 * User: estebanc
 * Date: 02/10/15
 * Time: 12:07
 */

namespace It121\AddressBundle\EventListener;


use It121\AddressBundle\Entity\PafPostcode;
use It121\AddressBundle\Entity\PostcodeIo;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;

class PafPostcodeListener
{
    private $PostcodeIoAPIService;

    public function setPostcodeIoAPIService($PostcodeIoAPIService) {
        $this->PostcodeIoAPIService = $PostcodeIoAPIService;
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        //$client = $this->container->get('box_uk_postcodes_io.client');
        $client = $this->PostcodeIoAPIService->get('box_uk_postcodes_io.client');

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof PafPostcode) {

                $postcodeIo = new PostcodeIo();
                $postcodeIo->setPafPostcode($entity);
                try{
                    $response = $client->lookup(array('postcode' => $entity->getPostcode()));

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

                } catch(\Exception $e){

                }

                $em->persist($postcodeIo);
                $logMetadata = $em->getClassMetadata("It121\AddressBundle\Entity\PostcodeIo");
                $uow->computeChangeSet($logMetadata, $postcodeIo);


                $entity->setPostcodeIo($postcodeIo);

                $em->persist($entity);
                $classMetadata = $em->getClassMetadata(get_class($entity));
                $uow->recomputeSingleEntityChangeSet($classMetadata, $entity);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }
    }
}