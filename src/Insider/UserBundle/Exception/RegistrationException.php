<?php

namespace Insider\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class RegistrationException extends AccountStatusException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey()
    {
        return 'Registration not complete.';
    }
}