<?php

namespace Application\Sonata\AdminBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timofey Cherniavsky <varloc2000@gmail.com>
 */
class MediaType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'hidden', array(
                'required' => false,
                'error_bubbling' => false,
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'groups' => array('submit'),
                        'message' => 'media_collection_item_required_message',
                    )),
                ),
            ))
            ->add('language', null, array(
                'error_bubbling' => false,
                'constraints' => array(
                    new Assert\NotBlank(array(
                        'groups' => array('submit', 'upload'),
                    )),
                ),
            ))
            ->add('file', 'file', array(
                'required' => false,
                'error_bubbling' => false,
                'constraints' => array(
                    new Assert\File(array(
                        'groups' => array('upload'),
                        'maxSize' => $options['file_max_size'],
                        'mimeTypes' => $options['file_mime_types'],
                        'mimeTypesMessage' => 'Only following mime type is available '
                            . implode(', ', $options['file_mime_types']),
                    ))
                ),
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setRequired(array(
                'file_mime_types',
            ))
            ->setAllowedTypes(array(
                'file_mime_types' => 'array',
            ))
            ->setDefaults(array(
                'file_max_size' => '600000000',
                'error_bubbling' => false,
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'sonata_type_media';
    }
}
