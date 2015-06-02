<?php

namespace Insider\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PromoExist extends Constraint
{
    public $message = 'Промо код не верный, уточните его у администратора';

    public $nullMessage = 'Укажите промо код';

    public function validatedBy()
    {
        return 'validator_promoexist';
    }

    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}