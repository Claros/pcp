<?php

namespace JE\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Login'
            ))
            ->add('plainPassword', 'password', array(
                'label' => 'Mot de passe',
                'required' => false,
            ))
            ->add('firstName', 'text', array(
                'label' => 'Prénom'
            ))
            ->add('lastName', 'text', array(
                'label' => 'Nom de famille'
            ))
            ->add('email', 'email', array(
                'label' => 'Email'
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone'
            ))
            ->add('group', 'entity', array(
                'label' => 'Poste',
                'class' => 'JEUserBundle:Group',
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JE\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'je_userbundle_user';
    }
}
