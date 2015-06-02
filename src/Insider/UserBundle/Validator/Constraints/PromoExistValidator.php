<?php

namespace Insider\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class PromoExistValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($promo, Constraint $constraint)
    {
        $em = $this->entityManager->getRepository('InsiderUserBundle:User');

        if (null === $promo) {
            $this->context->addViolationAt(
                'promo',
                $constraint->nullMessage
            );

            return;
        }

        $userEntity = $em->findOneByPromo($promo);

        if (!$userEntity) {
            $this->context->addViolationAt(
                'promo',
                $constraint->message
            );
        }
    }
}