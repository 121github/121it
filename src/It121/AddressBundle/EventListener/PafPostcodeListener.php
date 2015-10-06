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
        //$client = $this->PostcodeIoAPIService->get('box_uk_postcodes_io.client');

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
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

    public function postFlush(PostFlushEventArgs $eventArgs)
    {

    }
}