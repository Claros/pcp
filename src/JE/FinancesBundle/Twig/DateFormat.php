<?php

namespace JE\FinancesBundle\Twig;


class DateFormat extends \Twig_Extension
{

    public function getFunctions()
    {
        return array(
            'months' => new \Twig_Function_Method($this, 'months'),
        );
    }

    public function months()
    {
        return array(
            1 => 'Janvier',
            'Février',
            'Mars',
            'Avril',
            'Mai',
            'Juin',
            'Juillet',
            'Août',
            'Septembre',
            'Octobre',
            'Novembre',
            'Décembre',
        );
    }

    public function getName()
    {
        return 'date_format';
    }
}