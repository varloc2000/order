<?php

namespace Insider\OrderBundle\Admin;

use Insider\OrderBundle\Entity\Order;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class OrderAdmin extends Admin
{
    protected $formOptions = array(
        'validation_groups' => array()
    );

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    );


    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->andWhere('o.isActive = 1')
            ->andWhere('o.status != :status')
            ->setParameter('status', Order::STATUS_COMPLETE)
        ;

        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('user')
            ->add('title')
            ->add('createdAt')
            ->add('price')
            ->add('chinaPrice')
            ->add('delivery')
            ->add('quantity')
            ->add('category')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('photo')
            ->add('status', null, array('template' => 'SonataAdminBundle:CRUD:list_status.html.twig'))
            ->add('user', null, array('sortable' => false))
            ->add('title', null, array('template' => 'SonataAdminBundle:CRUD:list_title_with_date.html.twig'))
            ->add('price', null, array('template' => 'SonataAdminBundle:CRUD:list_price.html.twig'))
            ->add('chinaPrice', null, array('template' => 'SonataAdminBundle:CRUD:list_china_price.html.twig'))
            ->add('quantity')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'recover' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Order.Main', array(
                'class' => 'col-md-4',
            ))
                ->add('user')
                ->add('link')
                ->add('title')
            ->end()
            ->with('Order.Price', array(
                'class' => 'col-md-4',
            ))
                ->add('price', 'number')
                ->add('priceCurrency', null, array(
                    'empty_value' => false,
                ))
            ->end()
            ->with('Order.ChinaPrice', array(
                'class' => 'col-md-4',
            ))
                ->add('chinaPrice', 'number')
                ->add('chinaPriceCurrency')
            ->end()
            ->with('Order.Details', array(
                'class' => 'col-md-12',
            ))
                ->add('delivery')
                ->add('quantity', 'number')
                ->add('size')
                ->add('color')
                ->add('file', 'file', array())
                ->add('category')
                ->add('description', 'textarea')
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('user')
            ->add('link')
            ->add('title')
            ->add('price')
            ->add('priceCurrency')
            ->add('chinaPrice')
            ->add('chinaPriceCurrency')
//            ->add('delivery')
            ->add('quantity')
            ->add('size')
            ->add('color')
            ->add('photo')
            ->add('category')
            ->add('description')
            ->add('isActive')
            ->add('status')
        ;
    }
}
