<?php

namespace It121\ServerBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddSubtypeFieldSubscriber implements EventSubscriberInterface {
	
	private $propertyPathToSubtype;
	
	public function __construct($propertyPathToSubtype)
	{
		$this->propertyPathToSubtype = $propertyPathToSubtype;
	}
	
	public static function getSubscribedEvents()
	{
		return array(
				FormEvents::PRE_SET_DATA  => 'preSetData',
				FormEvents::PRE_SUBMIT    => 'preSubmit'
		);
	}
	
	private function addSubtypeForm($form, $type_id)
	{
		$formOptions = array(
				'class'         => 'ServerBundle:ServerSubtype',
				'empty_value'   => 'Select a subtype',
				'label'         => 'Subtype',
				'attr'          => array(
						'class' => 'subtype_selector',
				),
				'query_builder' => function (EntityRepository $repository) use ($type_id) {
					$qb = $repository->createQueryBuilder('subtype')
					->innerJoin('subtype.type', 'type')
					->where('type.id = :type')
					->orderBy('subtype.name', 'ASC')
					->setParameter('type', $type_id)
					;
					return $qb;
				}
		);
	
		$form->add($this->propertyPathToSubtype, 'entity', $formOptions);
	}
	
	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();
	
		if (null === $data) {
			return;
		}
	
		$accessor    = PropertyAccess::createPropertyAccessor();
	
		$subtype       = $accessor->getValue($data, $this->propertyPathToSubtype);
		$type_id = ($subtype) ? $subtype->getType()->getId() : null;
	
		$this->addSubtypeForm($form, $type_id);
	}
	
	public function preSubmit(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();
	
		$type_id = array_key_exists('type', $data) ? $data['type'] : null;
	
		$this->addSubtypeForm($form, $type_id);
	}
}