<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('cliFirstName')
                ->add('cliMiddleName')
                ->add('cliFirstSurname')
                ->add('cliSecondSurname')
                ->add('cliIdNumber')
                ->add('cliIdType')
                ->add('cliAddress')
                ->add('cliPhone1')
                ->add('cliPhone2')
                ->add('cliEmail')
                ->add('cliNote')
                ->add('cliActive')
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }


}
