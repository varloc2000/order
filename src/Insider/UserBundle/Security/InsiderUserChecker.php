<?php

namespace Insider\UserBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserChecker;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Insider\UserBundle\Exception\RegistrationException;
use Insider\UserBundle\Entity\User;


class InsiderUserChecker extends UserChecker
{
    public function checkPreAuth(UserInterface $user)
    {
        // check anything
        if (!$user instanceof User) {
            return;
        }

        if ($user->getStatus() == User::STATUS_REGISTERED && !$user->getConfirmationToken()) {
            $ex = new RegistrationException('Регистрация не закончена');
            $ex->setUser($user);
            throw $ex;
        }

        if ($user->getStatus() == User::STATUS_REGISTERED) {
            $ex = new DisabledException('User account is disabled.!!!');
            $ex->setUser($user);
            throw $ex;
        }

        if ($user->getStatus() == User::STATUS_BLOCKED) {
            // TODO вынести в транслятор
            $ex = new LockedException('Пользователь заблокирован.');
            $ex->setUser($user);
            throw $ex;
        }

        if ($user->getStatus() == User::STATUS_DELETED) {
            // TODO вынести в транслятор
            $ex = new LockedException('Пользователь удален');
            $ex->setUser($user);
            throw $ex;
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        parent::checkPostAuth($user);
    }
}