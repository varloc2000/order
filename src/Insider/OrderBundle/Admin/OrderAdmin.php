<?php

namespace Insider\OrderBundle\Admin;

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

        $query->andWhere('o.isActive = 1');

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
            ->add('price')
            ->add('priceCurrency')
            ->add('chinaPrice')
            ->add('chinaPriceCurrency')
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
            ->add('user', null, array('sortable' => false))
//            ->add('link')
            ->add('title')
            ->add('price')
//            ->add('priceCurrency')
            ->add('chinaPrice')
//            ->add('chinaPriceCurrency')
//            ->add('delivery')
            ->add('quantity')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'recover' => array(),
                )
            ))
//            ->add('size')
//            ->add('color')
//            ->add('category')
//            ->add('description')
//            ->add('isActive')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
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
            ->add('file', 'file', array())
            ->add('category')
            ->add('description')
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
