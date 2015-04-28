<?php

namespace Insider\OrderBundle\Admin;

use Insider\OrderBundle\Entity\DeliveryWeightPrice;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class DeliveryAdmin extends Admin
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
            ->add('title')
            ->add('priceCurrency')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
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
            ->add('title')
            ->add('description')
            ->add('priceCurrency', null, array(
                'required' => true,
            ))
            ->add('weights', 'sonata_type_collection', array(
                'type_options' => array(
                    'delete' => true,
                    'required' => false,
                )
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('priceCurrency')
            ->add('weights', null, array(
                'editable' => false,
            ))
        ;
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function prePersist($object)
    {
        /** @var DeliveryWeightPrice $deliveryWeight */
        foreach ($object->getWeights() as $deliveryWeight) {
            $deliveryWeight->setDelivery($object);
        }
    }

    /**
     * @param mixed $object
     * @return mixed|void
     */
    public function preUpdate($object)
    {
        /** @var DeliveryWeightPrice $deliveryWeight */
        foreach ($object->getWeights() as $deliveryWeight) {
            $deliveryWeight->setDelivery($object);
        }
    }
}
