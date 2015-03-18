<?php

namespace Insider\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class InsiderUserBundle extends Bundle
{
    public function getParent()
    {
        return 'UserBundle';
    }
}
