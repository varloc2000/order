<?php

namespace Insider\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Insider\UserBundle\Entity\Role;

class RoleAdmin extends Admin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
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
            ->add('name')
            ->add('parentRole', null, array('template' => 'AllBYUserBundle:Admin:parentRole_list.html.twig'))
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
            ->add('name')
            ->add('parentRole', 'choice', ['choices' => Role::getParentRoleNames()])
            ->add('accessByModules',  'sonata_type_collection', array( 'by_reference' => true ), array(
                                                                                'edit' => 'inline',
                                                                                'inline' => 'table',
                                                                                'sortable'  => 'position'
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
            ->add('name')
            ->add('parentRole', null, array('template' => 'AllBYUserBundle:Admin:parentRole_show.html.twig'))
            ->add('accessByModule', null, array('template' => 'AllBYUserBundle:Admin:accessByModule_show.html.twig'))
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

    public function preUpdate($object)
    {
        foreach ($object->getAccessByModules() as $accessByModule)
        {
            $accessByModule->setRole($object);
        }
    }

    public function prePersist($object)
    {
        foreach ($object->getAccessByModules() as $accessByModule)
        {
            $accessByModule->setRole($object);
        }
    }
}
