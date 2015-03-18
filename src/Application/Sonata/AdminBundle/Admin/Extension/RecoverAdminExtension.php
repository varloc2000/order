<?php

namespace Application\Sonata\AdminBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;

class RecoverAdminExtension extends AdminExtension
{
    /**
     * @param AdminInterface $admin
     * @param RouteCollection $collection
     */
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {
        $collection->add('recover', $admin->getRouterIdParameter() . '/recover', array(
            '_controller' => 'AdminBundle:CRUD:recover'
        ));
    }
}