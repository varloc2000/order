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
            ->add('balance')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('username', null, array('sortable' => false))
            ->add('email', null, array(
                'sortable' => false,
                'template' => 'SonataAdminBundle:CRUD:list_user_info.html.twig',
            ))
            ->add('balance', null, array('template' => 'SonataAdminBundle:CRUD:list_user_currency_balance.html.twig'))
            ->add('createdAt', null, array('template' => 'SonataAdminBundle:CRUD:list_user_created_at.html.twig'))
            ->add('status', 'choice', array('choices' => User::getStatusNames(), 'sortable' => false))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'refill' => array(),
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
            ->with('User.Main', array(
                'class' => 'col-md-4',
            ))
                ->add('username')
                ->add('email')
                ->add('role', null, array('empty_value' => false))
                ->add('plainPassword', 'text', array('required' => $passRequired))
                ->add('status', 'choice', array('choices' => User::getStatusNames(), 'required'=> false))
                ->add('currency', null, array(
                    'required' => true,
                ))
                ->add('promo', null, array('attr' => array('readonly' => 'readonly')))
                ->add('balance', 'number', array(
                    'attr' => array('readonly' => 'readonly'),
                    'help' => '(В долларах)'
                ))
            ->end()
            ->with('Profile.Additional', array(
                'class' => 'col-md-8',
            ))
                ->add('firstName')
                ->add('lastName')
                ->add('phone')
                ->add('city')
                ->add('skype')
                ->add('vk')
                ->add('additional', 'textarea', array(
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                ))
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
            ->add('username')
            ->add('email')
            ->add('additional')
            ->add('lastLogin')
            ->add('createdAt')
            ->add('role')
            ->add('status', 'choice', array('choices' => User::getStatusNames()))
        ;
    }

    public function prePersist($object)
    {
        $object->setPromo($this->generatePromoCode());
    }


    /**
     * @return string
     */
    protected function generatePromoCode()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";

        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }

        return $res;
    }
}
