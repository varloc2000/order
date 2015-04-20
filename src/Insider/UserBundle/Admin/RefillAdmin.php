<?php

namespace Insider\UserBundle\Admin;

use FOS\UserBundle\Model\UserManagerInterface;
use Insider\UserBundle\Entity\Refill;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class RefillAdmin extends Admin
{
    /**
     * @var UserManagerInterface
     */
    protected $userManager;

    protected $parentAssociationMapping = 'user';

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    );

    /**
     * {@inheritDoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('edit');
    }

    /**
     * {@inheritDoc}
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdAt')
            ->add('type', 'doctrine_orm_string', array(), 'choice', array('choices' => Refill::getStatusNames()))
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('createdAt', null, array(
                'label' => 'refill.created_at',
            ))
            ->add('author.username')
            ->add('type', 'choice', array('choices' => Refill::getStatusNames()))
            ->add('amount_in_admin_currency', null, array(
                'template' => 'SonataAdminBundle:CRUD:list_refill_total_in_user_currency.html.twig',
            ))
            ->add('comment')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user', 'entity_hidden', array(
                'class' => 'Insider\UserBundle\Entity\User'
            ), array(
                'admin_code' => 'insider_user.admin.user',
            ))
            ->add('type', 'choice', array(
                'expanded' => true,
                'multiple' => false,
                'choices' => Refill::getStatusNames(),
                'attr' => array(
                    'class' => 'list-inline',
                )
            ))
            ->add('amount')
            ->add('currency', null, array(
                'required' => true,
            ))
            ->add('comment', 'textarea', array(
                'attr' => array(
                    'class' => 'form-control'
                ),
            ))
        ;
    }

    /**
     * {@inheritDoc}
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

    /**
     * Set author of refill
     * {@inheritDoc}
     */
    public function prePersist($object)
    {
        if (!$object->getAuthor()) {
            $user = $this->getConfigurationPool()
                ->getContainer()
                ->get('security.context')
                ->getToken()
                ->getUser()
            ;

            $object->setAuthor($user);
        }
    }

    /**
     * Add to user balance created refill in dollars
     * {@inheritDoc}
     */
    public function postPersist($object)
    {
        if ($object->getAmount() && $user = $object->getUser()) {
            $user->increaseBalance($object->getAmount() * $object->getCurrency()->getCourse());

            $this->userManager->updateUser($user);
        }
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }
}
