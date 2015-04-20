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
                ->add('username', null, array(
                    'attr' => array('readonly' => 'readonly'),
                ))
                ->add('email', null, array(
                    'attr' => array('readonly' => 'readonly'),
                ))
                ->remove('role')
                ->remove('plainPassword')
                ->remove('status')
            ->end()
            ->with('Profile.Additional', array(
                'class' => 'col-md-8',
                'description' => 'Чтобы было легче с вами связаться, пожалуйста, укажите личные данные:',
            ))
            ->end()
        ;
    }
}
