<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckBoxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class LoanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                //->add('loaCode')
                ->add('loaAmount')
                //->add('loaReturnAmount')
                ->add('loaRateInterestByDefault')
                ->add('loaRateInterest')
                ->add('loaClient', HiddenType::class, array(
                    'mapped'=>false,
                    "attr"=>array( "class"=>"" ),
        
                ))
                /*
                ->add('loaFirstPayment', DateType::class, array(
                    'mapped'=>false,
                    'widget' => 'single_text',
                    
                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => 'datepicker'],
        
                ))
                */
                ->add('loaDescription', TextareaType::class, array(
                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => ''],
                    'required'   => false,
                ))
                
                ->add('loaDeadline', DateType::class, array(
                    'widget' => 'single_text',

                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => 'datepicker'],
                ))
                ->add('loaRecurringDayPayment')
                ->add('loaLoanMade', DateType::class, array(
                    'widget' => 'single_text',
                    
                    // prevents rendering it as type="date", to avoid HTML5 date pickers
                    'html5' => false,

                    // adds a class that can be selected in JavaScript
                    'attr' => ['class' => 'datepicker'],
                ))
                ->add('loaQuotas')
                
                //->add('loaPending')
                ->add('loaCompleted')
                //->add('loaActive')
                //->add('loaCreated')
                //->add('cli')
                ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Loan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_loan';
    }


}
