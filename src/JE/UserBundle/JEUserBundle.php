<?php

namespace JE\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JEUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
