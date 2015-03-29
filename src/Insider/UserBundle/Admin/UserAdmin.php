<?php

namespace Insider\UserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Insider\UserBundle\Entity\User;

class UserAdmin extends Admin
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
            ->add('username')
            ->add('email')
            ->add('isActive')
            ->add('status', 'doctrine_orm_choice', array(), 'choice', array('choices' => User::getStatusNames()))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('username', null, array('sortable' => false))
            ->add('email', null, array('sortable' => false))
            ->add('lastLogin')
            ->add('createdAt')
            ->add('status', 'choice', array('choices' => User::getStatusNames(), 'sortable' => false))
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
        $passRequired = (is_null($this->getSubject()->getId())) ? true : false;
        $formMapper
            ->add('username')
            ->add('email')
            ->add('role', null, array('empty_value' => false))
            ->add('plainPassword', 'text', array('required' => $passRequired))
            ->add('status', 'choice', array('choices' => User::getStatusNames(), 'required'=> false))
            ->add('promo', null, array('attr' => array('readonly' => 'readonly')))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('lastLogin')
            ->add('createdAt')
            ->add('role')
            ->add('status', 'choice', array('choices' => User::getStatusNames()))
        ;
    }
}
