<?php

namespace It121\CalldevBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AdvisorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 */
class AdvisorRepository extends EntityRepository
{
    public function getRealExtension($callDate, $callFrom) {

        $em = $this->getEntityManager();

        $repo = $em->getRepository('CalldevBundle:AdvisorPhones');
        $query = $repo->createQueryBuilder('a')
            ->select('a')
            ->where('a.start <= :callDate AND a.finish >= :callDate')
            ->andWhere('a.phone = :callFrom')
            ->orderBy("a.date","DESC")
            ->setParameter('callDate', $callDate)
            ->setParameter('callFrom', $callFrom)
        ;

        return $query->getQuery()->getResult();

    }
}