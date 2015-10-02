<?php

namespace It121\AddressBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Range;

class ImportPafType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('file', 'file', array('required' => true, 'label' => 'PAF (csv file)'))
        ;
    }

/**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'It121\AddressBundle\Entity\ImportPaf',
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'import_form';
    }
}
