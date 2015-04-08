<?php

namespace Insider\UserBundle\Admin;

use Insider\UserBundle\Entity\Refill;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class RefillAdmin extends Admin
{
    protected $parentAssociationMapping = 'user';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('amount')
            ->add('currency')
            ->add('comment')
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Refill.Main', array(
                'class' => 'col-md-4',
            ))
                ->add('user', 'hidden', array(
                    'data_class' => 'Insider\UserBundle\Entity\User'
                ), array(
                    'admin_code' => 'insider_user.admin.user',
                ))
                ->add('amount')
                ->add('currency', null, array(
                    'required' => true,
                ))
                ->add('comment', 'textarea')
            ->end()
            ->with('Refill.History', array(
                'class' => 'col-md-8',
                'description' => 'тут будет история'
            ))
//                ->add('user.refill', 'collection')
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
            ->add('amount')
            ->add('currency')
            ->add('comment')
        ;
    }
}
