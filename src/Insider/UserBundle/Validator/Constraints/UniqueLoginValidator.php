<?php

namespace Insider\UserBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class UniqueLoginValidator extends ConstraintValidator
{

    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($user, Constraint $constraint)
    {
        $i=0;
        $em = $this->entityManager->getRepository('InsiderUserBundle:User');
        $userName = $user->getUsername();
        $userEntity = $em->findOneByUsername($userName);
        if (isset($userEntity) && $userEntity->getId() !== $user->getId()){
            while($userEntity){
                $i++;
                $userEntity = $this->entityManager->getRepository('InsiderUserBundle:User')->findOneByUsername($userName.$i);
            }
            if ($i != 0){
                $this->context->addViolationAt(
                    'username',
                    $constraint->message,
                    array(
                        '%string%' => $userName,
                        '%new_string%' => $userName.$i
                    )
                );
            }
        }
    }
}