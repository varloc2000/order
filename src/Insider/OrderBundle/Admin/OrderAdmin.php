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

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
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
            ->add('category')
            ->add('description')
            ->add('isActive')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('user', null, array('sortable' => false))
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
        ;
    }

    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(array(
                'isActive' => array(
                    'value' => true,
                )
            ),
            $this->datagridValues

        );
        return parent::getFilterParameters();
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
