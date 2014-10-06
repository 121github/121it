<?php

namespace It121\ProjectBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{
	
	/**
	 * Get All ordered
	 *
	 *
	 * @return array Projects
	 */
	public function findAllWithServer(){
	
		$eManager = $this->getEntityManager();
	
		$query = $eManager->createQueryBuilder()
		->select('p')
		->from('ProjectBundle:Project', 'p')
		->innerJoin('p.server', 's')
		->innerJoin('s.subtype', 'st')
		->orderBy('st.id', 'ASC')
		;
	
		$project_ar = $query->getQuery()->getResult();
	
		return $project_ar;
	}
	
	/**
	 * Get All ordered by subtype
	 *
	 *
	 * @return array Projects
	 */
	public function findAll($subtype = null){
	
		$eManager = $this->getEntityManager();
	
		$query = $eManager->createQueryBuilder()
		->select('p')
		->from('ProjectBundle:Project', 'p')
		->innerJoin('p.server', 's')
		->innerJoin('s.subtype', 'st')
		;
		
		if ($subtype) {
			$query
				->where('st.name = :subtype')
				->setParameter('subtype', $subtype);	
		}
	
		$query->orderBy('st.id', 'ASC');
		
		$project_ar = $query->getQuery()->getResult();
	
		return $project_ar;
	}
	
	
	/**
	 * Get Projects on an Environment
	 *
	 *
	 * @return array Projects
	 */
	public function findEnvironment($name){
	
		$eManager = $this->getEntityManager();
	
		$query = $eManager->createQueryBuilder()
		->select('p')
		->from('ProjectBundle:Project', 'p')
		->innerJoin('p.server', 's')
		->innerJoin('s.environment', 'e')
		->where('e.name = :environment')
		->setParameter('environment', $name)
		;
		
		$project_ar = $query->getQuery()->getResult();
	
		return $project_ar;
	}
}
