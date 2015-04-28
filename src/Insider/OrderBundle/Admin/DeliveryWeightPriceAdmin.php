<?php

namespace Insider\OrderBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;

class DeliveryWeightPriceAdmin extends Admin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('weight', null, array(
                'required' => true,
            ))
            ->add('price', null, array(
                'required' => true,
            ))
        ;
    }
}
