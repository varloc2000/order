<?php

namespace Insider\UserBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;

class ProfileAdmin extends UserAdmin
{
    protected $baseRouteName = 'profile';

    protected $baseRoutePattern = 'profile';

    /**
     * {@inheritdoc}
     */
    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $user = $this
            ->getConfigurationPool()
            ->getContainer()
            ->get('security.context')
            ->getToken()
            ->getUser()
        ;

        $query
            ->andWhere('o.id = :id')
            ->setParameter('id', $user->getId())
        ;

        return $query;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->with('User.Main', array(
                'class' => 'col-md-4',
            ))
                ->remove('role')
                ->remove('plainPassword')
                ->remove('status')
                ->remove('status')
                ->remove('status')
                ->add('balance', 'number', array('attr' => array('readonly' => 'readonly')))
            ->end()
            ->with('Profile.Additional', array(
                'class' => 'col-md-8',
                'description' => 'Чтобы было легче с вами связаться, пожалуйста, укажите личные данные:',
            ))
                ->add('firstName')
                ->add('lastName')
                ->add('phone')
                ->add('skype')
                ->add('vk')
            ->end()
        ;
    }
}
