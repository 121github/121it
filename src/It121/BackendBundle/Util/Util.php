<?php
namespace It121\BackendBundle\Util;

class Util
{
	
	static public function setCreateAuditFields($entity, $user_id)
	{
		$entity->setCreatedBy($user_id);
		$entity->setCreatedDate(new \DateTime('now'));
		$entity->setModifiedBy($user_id);
		$entity->setModifiedDate(new \DateTime('now'));
		
		return $entity;
	}
	
	static public function setModifyAuditFields($entity, $user_id)
	{
		$entity->setModifiedBy($user_id);
		$entity->setModifiedDate(new \DateTime('now'));
	
		return $entity;
	}

	//format postcode
	static public function postcodeFormat($postcode)
	{
		//trim and remove spaces
		$cleanPostcode = preg_replace("/[^A-Za-z0-9]/", '', $postcode);

		//make uppercase
		$cleanPostcode = strtoupper($cleanPostcode);

		//if 5 charcters, insert space after the 2nd character
		if(strlen($cleanPostcode) == 5)
		{
			$postcode = substr($cleanPostcode,0,2) . " " . substr($cleanPostcode,2,3);
		}

		//if 6 charcters, insert space after the 3rd character
		elseif(strlen($cleanPostcode) == 6)
		{
			$postcode = substr($cleanPostcode,0,3) . " " . substr($cleanPostcode,3,3);
		}


		//if 7 charcters, insert space after the 4th character
		elseif(strlen($cleanPostcode) == 7)
		{
			$postcode = substr($cleanPostcode,0,4) . " " . substr($cleanPostcode,4,3);
		}

		return $postcode;
	}
		
}