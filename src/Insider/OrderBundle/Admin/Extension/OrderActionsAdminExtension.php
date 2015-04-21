<?php

namespace Insider\OrderBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Add additional actions for orders
 *
 * Class ActionsOrderAdminExtension
 * @package Insider\OrderBundle\Admin\Extension
 */
class OrderActionsAdminExtension extends AdminExtension
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
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {
        $collection->add('change_status', $admin->getRouterIdParameter() . '/{status}/change_status', array(
            '_controller' => 'AdminBundle:CRUD:changeOrderStatus'
        ));
    }

    /**
     * @return bool
     */
    protected function isClient()
    {
        return $this->context->isGranted('ROLE_CLIENT');
    }
}
