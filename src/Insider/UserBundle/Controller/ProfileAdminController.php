<?php

namespace Insider\UserBundle\Controller;

use Application\Sonata\AdminBundle\Controller\CRUDController as Controller;

class ProfileAdminController extends Controller
{
    public function listAction()
    {
        $user = $this->getUser();

        return $this->redirectTo($user);
    }
}
