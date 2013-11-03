<?php

namespace JE\UserBundle\Twig;


use JE\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class UserHelper extends \Twig_Extension
{
    /**
     * @var Router
     */
    protected $router;

    function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getFilters()
    {
        return array(
            'linkUser' => new \Twig_Filter_Method($this, 'linkUser', array('is_safe' => array('html'))),
        );
    }

    public function linkUser(User $user, array $attr = null)
    {
        $html = "<a href='".$this->router->generate('je_user_users_profile', array('id'=>$user->getId()))."'";

        if($attr !== null)
            foreach($attr as $key => $value)
                $html .= " $key='$value'";

        $html .= ">$user</a>";

        return $html;
    }

    public function getName()
    {
        return 'user_helper';
    }
}