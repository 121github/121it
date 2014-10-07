<?php

namespace It121\ServerBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;

class AddTypeFieldSubscriber implements EventSubscriberInterface {
	
	private $propertyPathToSubtype;
	
	public function __construct($propertyPathToSubtype)
	{
		$this->propertyPathToSubtype = $propertyPathToSubtype;
	}
	
	public static function getSubscribedEvents()
	{
		return array(
				FormEvents::PRE_SET_DATA => 'preSetData',
				FormEvents::PRE_SUBMIT   => 'preSubmit'
		);
	}
	
	private function addTypeForm($form, $type = null)
	{
		$formOptions = array(
				'class'         => 'ServerBundle:ServerType',
				'empty_value'   => 'Select the Type',
				'label'         => 'Type',
				'mapped'        => false,
				'attr'          => array(
						'class' => 'province_selector',
				),
				'query_builder' => function(EntityRepository $repository) {
            			return $repository->createQueryBuilder('t')
            			->orderBy('t.name', 'ASC')
            			;
	
					return $qb;
				}
		);
	
		if ($type) {
			$formOptions['data'] = $type;
		}
	
		$form->add('type','entity', $formOptions);
	}
	
	public function preSetData(FormEvent $event)
	{
		$data = $event->getData();
		$form = $event->getForm();
	
		if (null === $data) {
			return;
		}
	
		$accessor = PropertyAccess::getPropertyAccessor();
	
		$subtype        = $accessor->getValue($data, $this->propertyPathToSubtype);
		$type    = ($subtype) ? $subtype->getType() : null;
	
		$this->addTypeForm($form, $type);
	}
	
	public function preSubmit(FormEvent $event)
	{
		$form = $event->getForm();
	
		$this->addTypeForm($form);
	}
}