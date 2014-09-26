<?php

namespace It121\ServerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use It121\UserBundle\Form\UserDetailType;

class ServerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('domain')
            ->add('path')
            ->add('user')
            ->add('password')
			->add('port')
            ->add('type', 'entity', array(
            		'class'         => 'It121\\ServerBundle\\Entity\\ServerType',
            		'empty_value'   => 'Select a type',
            		'query_builder' => function(EntityRepository $repository) {
            			return $repository->createQueryBuilder('t')
            			->orderBy('t.name', 'ASC');
            		},
            ))
            ->add('subtype', 'entity', array(
            		'class'         => 'It121\\ServerBundle\\Entity\\ServerSubtype',
            		'empty_value'   => 'Select a subtype',
            		'query_builder' => function(EntityRepository $repository) {
            			return $repository->createQueryBuilder('st')
            			->orderBy('st.name', 'ASC');
            		},
            ))
            ->add('sendEmail', 'checkbox')
            ->add('monitoring', 'checkbox')
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
