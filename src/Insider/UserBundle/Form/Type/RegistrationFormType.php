<?php

namespace Insider\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('promo', 'text', array(
                'translation_domain' => 'FOSUserBundle',
                'mapped' => 'refererCode',
                'label' => 'form.promo'
            ))
            ->add('agree', 'checkbox', array(
                'mapped' => false,
                'label' => 'form.agreement'
            ))
        ;
    }

    public function getName()
    {
        return 'insider_user_registration';
    }
}
