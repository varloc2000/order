<?php

namespace Application\Sonata\AdminBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timofey Cherniavsky <varloc2000@gmail.com>
 */
class MediaCollectionType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('media_collection', 'collection', array(
            'type' => new MediaType(),
            'prototype' => true,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false,
            'cascade_validation' => true,
            'options' => array(
                'data_class' => $options['media_data_class'],
                'file_mime_types' => $options['media_mime_types'],
                'file_max_size' => $options['media_max_size'],
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['btn_add'] = $options['btn_add'];
        $view->vars['btn_list'] = $options['btn_list'];
        $view->vars['btn_delete'] = $options['btn_delete'];
        $view->vars['btn_catalogue'] = $options['btn_catalogue'];
        $view->vars['admin'] = $options['admin'];
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'media_data_class',
                'media_mime_types',
                'admin',
            ))
            ->setAllowedTypes(array(
                'media_data_class' => 'string',
                'media_mime_types' => 'array',
            ))
            ->setDefaults(array(
                // Custom options
                'media_max_size' => '600000000',
                'translation_domain' => 'SonataAdminBundle',
                // Stolen from sonata ModelType
                'btn_add' => 'link_add',
                'btn_list' => 'link_list',
                'btn_delete' => 'link_delete',
                'btn_catalogue' => 'SonataAdminBundle',
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'sonata_type_media_collection';
    }
}
