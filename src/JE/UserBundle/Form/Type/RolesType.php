<?php
namespace JE\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RolesType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'Utilisateurs' => array(
                    'ROLE_USERS_VIEW' => 'ROLE_USERS_VIEW',
                    'ROLE_USERS_ADD' => 'ROLE_USERS_ADD',
                    'ROLE_USERS_EDIT' => 'ROLE_USERS_EDIT',
                    'ROLE_USERS_REMOVE' => 'ROLE_USERS_REMOVE',
                ),
                'Postes' => array(
                    'ROLE_GROUP_VIEW' => 'ROLE_GROUP_VIEW',
                    'ROLE_GROUP_ADD' => 'ROLE_GROUP_ADD',
                    'ROLE_GROUP_EDIT' => 'ROLE_GROUP_EDIT',
                    'ROLE_GROUP_REMOVE' => 'ROLE_GROUP_REMOVE',
                ),
                'Trésorerie' => array(
                    'ROLE_TREZ_VIEW' => 'ROLE_TREZ_VIEW',
                    'ROLE_TREZ_ADD' => 'ROLE_TREZ_ADD',
                    'ROLE_TREZ_EDIT' => 'ROLE_TREZ_EDIT',
                    'ROLE_TREZ_REMOVE' => 'ROLE_TREZ_REMOVE',
                ),
            ),
            'translation_domain' => 'roles',
            'multiple' => true,
            'expanded' => true,
            'attr' => array(
                'class' => 'staked-checkboxes',
            )
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'roles';
    }
}