<?php

namespace Insider\OrderBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Restrict list of orders by user only for client roles
 *
 * Class UserOrderAdminExtension
 * @package Insider\OrderBundle\Admin\Extension
 */
class UserOrderAdminExtension extends AdminExtension
{
    /**
     * @var SecurityContext
     */
    protected $context;

    /**
     * @param SecurityContext $context
     */
    public function setSecurityContext(SecurityContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {
        if ($this->isClient()) {
            $user = $this->context
                ->getToken()
                ->getUser()
            ;

            $query->getQueryBuilder()
                ->andWhere('o.user = :user')
                ->setParameter('user', $user->getId())
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureFormFields(FormMapper $form)
    {
        if ($this->isClient()) {
            $form->remove('user');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureListFields(ListMapper $list)
    {
        if ($this->isClient()) {
            $list->remove('user');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->remove('user');
    }

    /**
     * @return bool
     */
    protected function isClient()
    {
        return $this->context->isGranted('ROLE_CLIENT');
    }
}
