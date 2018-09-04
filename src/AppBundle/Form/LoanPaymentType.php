<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LoanPaymentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('lpaMaxPaymentDate', DateType::class, array(
                    'widget' => 'single_text',
                    
                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => ''],
                ))
                ->add('lpaPaidDate', DateType::class, array(
                    'widget' => 'single_text',
                    
                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => ''],
                ))
                //->add('lpaPaidRateInterest')
                //->add('lpaPaidCapital')
                ->add('lpaCurrentRateInterest')
                //->add('lpaCurrentAmount')
                ->add('lpaTotalAmountPaid')
                ->add('lpaNote', TextareaType::class, array(
                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => ''],
                    'required'   => false,
                ))
                ->add('loaNextPaymentDate', DateType::class, array(
                    'mapped'=>false,
                    'widget' => 'single_text',
                    
                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => ''],
        
                ))
                /*
                ->add('lpaOptionComplete', ChoiceType::class, array('mapped' => false,
                    'expanded' => true,
                    'multiple' => false,
                    'choices' => array(0=>'Yes', 1=>'No'),
                    'required' => true,
                ))
                */
                ->add('loaNextInterestRate', TextType::class, array(
                    'mapped'=>false,        
                ))
                //->add('loa')
                ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\LoanPayment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_loanpayment';
    }


}
