<?php

namespace Insider\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Insider\UserBundle\Entity\User;

class ProfileAdmin extends UserAdmin
{
    protected $baseRouteName = 'profile';

    protected $baseRoutePattern = 'profile';

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $user = $this
            ->getConfigurationPool()
            ->getContainer()
            ->get('security.context')
            ->getToken()
            ->getUser()
        ;

        $query
            ->andWhere('o.id = :id')
            ->setParameter('id', $user->getId())
        ;

        return $query;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->remove('role')
            ->remove('plainPassword')
            ->remove('status')
        ;
    }
}
