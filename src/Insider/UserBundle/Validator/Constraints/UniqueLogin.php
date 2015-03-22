<?php

namespace Insider\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class UniqueLogin extends Constraint
{
    public $message = 'Логин "%string%" уже используется, попробуйте "%new_string%" ';

    public function validatedBy()
    {
        return 'validator_uniquelogin';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}