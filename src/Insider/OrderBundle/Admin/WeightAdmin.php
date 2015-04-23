<?php

namespace Insider\OrderBundle\Admin;

use Insider\OrderBundle\Entity\Weight;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

class WeightAdmin extends Admin
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
            ->add('label')
            ->add('minless')
            ->add('minWeight')
            ->add('maxless')
            ->add('maxWeight')
            ->add('type', 'doctrine_orm_string', array(), 'choice', array('choices' => Weight::getTypeNames()))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('type', 'choice', array('choices' => Weight::getTypeNames()))
            ->add('condition', null, array('template' => 'SonataAdminBundle:CRUD:list_weight_condition.html.twig'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
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
            ->add('type', 'choice', array(
                'expanded' => true,
                'choices' => Weight::getTypeNames(),
                'attr' => array(
                    'class' => 'list-inline'
                )
            ))
            ->add('label', null, array('help' => 'Необязательная пометка для клиента'))
            ->add('minless', null, array(
                'required' => false,
                'help' => 'Если у диапазона нет нижней границы',
            ))
            ->add('minWeight')
            ->add('maxless', null, array(
                'required' => false,
                'help' => 'Если у диапазона нет верхней границы',
            ))
            ->add('maxWeight')
            ->add('custom', null, array('help' => 'Особое значение если выбран тип "Особый вес"'))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        if ($object instanceof Weight) {
            if ($object->isCustom()) {
                $errorElement
                    ->with('custom')
                        ->assertNotBlank()
                    ->end()
                ;
            } else {
                if (false === $object->getMinless()) {
                    $errorElement
                        ->with('minWeight')
                            ->assertNotBlank()
                        ->end()
                    ;
                }
                if (false === $object->getMaxless()) {
                    $errorElement
                        ->with('maxWeight')
                            ->assertNotBlank()
                        ->end()
                    ;
                }
            }
        }
    }
}
