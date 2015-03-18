<?php

namespace Insider\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

/**
 * UserRepository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param integer $roleId
     * @return array
     */
    public function getUsersByParentRoleId($roleId)
    {
        return $this->createQueryBuilder('u')
            ->add('from', 'Insider\UserBundle\Entity\User u INDEX BY u.id') // Hook to use id as query result array indexes
            ->innerJoin('Insider\UserBundle\Entity\Role', 'r', Expr\Join::WITH, 'u.role = r.id')
            ->where('r.parentRole = :roleId')
            ->setParameter('roleId', $roleId)
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->execute()
        ;
    }
}
