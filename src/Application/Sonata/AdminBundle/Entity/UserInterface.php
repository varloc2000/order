<?php

namespace Application\Sonata\AdminBundle\Entity;

use Insider\UserBundle\Entity\User;

interface UserInterface
{
    public function setUser(User $user = null);

    public function getUser();
}
