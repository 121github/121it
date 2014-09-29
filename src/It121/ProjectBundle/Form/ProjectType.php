<?php

namespace It121\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
            ->add('name')
            ->add('group', 'entity', array(
            		'class'         => 'It121\\ProjectBundle\\Entity\\ProjectGroup',
            		'empty_value'   => 'Select a Group',
            		'query_builder' => function(EntityRepository $repository) {
            			return $repository->createQueryBuilder('g')
            			->orderBy('g.name', 'ASC');
            		},
            ))
            ->add('startDate', 'datetime', array(
            		'widget' => 'single_text',
            		'format' => 'Y-M-d HH:mm'
        	))
            ->add('endDate', 'datetime', array(
            		'widget' => 'single_text',
            		'format' => 'Y-M-d HH:mm',
            		'required' => false,
        	))
            ->add('server', 'entity', array(
            		'class'         => 'It121\\ServerBundle\\Entity\\Server',
            		'empty_value'   => 'Select a Website',
            		'query_builder' => function(EntityRepository $repository) {
            			return $repository->findServersByTypeQuery('Website');
            		},
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
            'data_class' => 'It121\ProjectBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'it121_projectbundle_project';
    }
}
