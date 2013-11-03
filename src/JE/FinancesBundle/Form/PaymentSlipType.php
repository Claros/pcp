<?php

namespace JE\FinancesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PaymentSlipType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ref', 'text', array(
                'label' => 'Référence',
            ))
            ->add('bv', 'text', array(
                'label' => 'BV',
            ))
            ->add('createdAt', 'datepicker', array(
                'label' => "Date",
            ))
            ->add('client', 'text', array(
                'label' => 'Client',
            ))
            ->add('student', 'text', array(
                'label' => 'Réalisateur',
            ))
            ->add('amount', 'text', array(
                'label' => 'Montant HT',
            ))
            ->add('numberOfDays', 'text', array(
                'label' => 'Nombre de JEH',
            ))
            ->add('file', 'file', array(
                'label' => 'Fichier',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JE\FinancesBundle\Entity\PaymentSlip'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_financesbundle_paymentslip';
    }
}
