<?php

namespace Application\Sonata\AdminBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

class MediaCollectionAdminExtension extends AdminExtension
{
    /**
     * @param AdminInterface $admin
     * @param RouteCollection $collection
     */
    public function configureRoutes(AdminInterface $admin, RouteCollection $collection)
    {
        $collection
            ->add(
                'create_many',
                'create/many',
                array(
                    '_controller' => 'AdminBundle:CRUD:createMany'
                )
            )
            ->add(
                'upload',
                'upload',
                array(
                    '_controller' => 'AdminBundle:CRUD:uploadMedia'
                ),
                array(),
                array('method' => 'POST')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('isFinished');
    }
}