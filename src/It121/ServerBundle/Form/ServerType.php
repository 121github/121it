<?php

namespace It121\ServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use It121\ServerBundle\Form\EventListener\AddSubtypeFieldSubscriber;
use It121\ServerBundle\Form\EventListener\AddTypeFieldSubscriber;

class ServerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$propertyPathToSubtype = 'subtype';
    	
    	$builder
    	->addEventSubscriber(new AddSubtypeFieldSubscriber($propertyPathToSubtype))
    	->addEventSubscriber(new AddTypeFieldSubscriber($propertyPathToSubtype))
    	;
    	
        $builder
            ->add('name')
            ->add('domain')
            ->add('path')
            ->add('user')
            ->add('password')
			->add('port')
            ->add('environment', 'entity', array(
            		'class'         => 'It121\\ServerBundle\\Entity\\ServerEnvironment',
            		'empty_value'   => 'Select an environment',
            		'query_builder' => function(EntityRepository $repository) {
            			return $repository->createQueryBuilder('se')
            			->orderBy('se.name', 'ASC');
            		},
            		'required' => false,
            ))
            ->add('rssUrl')
            ->add('sendEmail', 'checkbox', array(
            	'required' => false,
            ))
            ->add('monitoring', 'checkbox', array(
            		'required' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'It121\ServerBundle\Entity\Server'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'it121_serverbundle_server';
    }
}
