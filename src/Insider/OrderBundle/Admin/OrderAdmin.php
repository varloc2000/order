<?php

namespace Insider\OrderBundle\Admin;

use Insider\OrderBundle\Entity\Order;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class OrderAdmin extends Admin
{
    protected $formOptions = array(
        'validation_groups' => array()
    );

    protected $datagridValues = array(
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    );

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        $query
            ->andWhere('o.isActive = 1')
            ->andWhere('o.status != :status')
            ->setParameter('status', Order::STATUS_COMPLETE)
        ;

        return $query;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('user')
            ->add('title')
            ->add('createdAt')
            ->add('price')
            ->add('chinaPrice')
            ->add('delivery')
            ->add('quantity')
            ->add('category')
            ->add('status', 'doctrine_orm_string', array(), 'choice', array('choices' => Order::getStatusNames()))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('path', null, array('template' => 'SonataAdminBundle:CRUD:list_path.html.twig'))
            ->add('status', null, array('template' => 'SonataAdminBundle:CRUD:list_status.html.twig'))
            ->add('user.username', null, array(
                'sortable' => false,
//                'admin_code' => 'insider_user.admin.user',
            ))
            ->add('title', null, array('template' => 'SonataAdminBundle:CRUD:list_title_with_date.html.twig'))
            ->add('delivery')
            ->add('total', null, array('template' => 'SonataAdminBundle:CRUD:list_order_total_price_in_user_currency.html.twig'))
            ->add('weight')
//            ->add('chinaPrice', null, array('template' => 'SonataAdminBundle:CRUD:list_china_price_in_user_currency.html.twig'))
            ->add('quantity')
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
        $this->getShow();
        $order = $this->getSubject();
        $fileFieldOptions = array();

        // Get order image
        if ($order && ($cdnPath = $order->getPath())) {
            /** @var \Clarity\CdnBundle\Filemanager\Filemanager $filemanager */
            $filemanager = $this->getConfigurationPool()->getContainer()->get('clarity_cdn.filemanager');
            $file = $filemanager->get($cdnPath);

            if (is_object($file)) {
                $fileFieldOptions['required'] = false;
                $fileFieldOptions['help'] = '<img src="' . $file->getWebPath() . '" alt="' . $order->getTitle() . '" title="' . $order->getTitle() . '" style="max-width: 100px"/>';
            }
        } else {
            $fileFieldOptions['help'] = 'Тут будет предпросмотр фотографии';
        }

        $formMapper
            ->with('Order.Main', array(
                'class' => 'col-md-4',
            ))
                ->add('user', null, array(), array(
                    'admin_code' => 'insider_user.admin.user',
                    'required' => true,
                ))
                ->add('link')
                ->add('title')
            ->end()
            ->with('Order.Price', array(
                'class' => 'col-md-4',
            ))
                ->add('price', 'number', array(
                    'property_path' => 'priceInOrderCurrency'
                ))
                ->add('priceCurrency', null, array(
                    'empty_value' => false,
                ))
            ->end()
            ->with('Order.ChinaPrice', array(
                'class' => 'col-md-4',
            ))
                ->add('chinaPrice', 'number', array(
                    'property_path' => 'chinaPriceInOrderCurrency'
                ))
                ->add('chinaPriceCurrency')
            ->end()
            ->with('Order.Details', array(
                'class' => 'col-md-12',
            ))
                ->add('delivery')
                ->add('quantity', 'number')
                ->add('weight')
                ->add('size', null, array(
                    'required' => false,
                ))
                ->add('color', null, array(
                    'required' => false,
                ))
                ->add('file', 'file', $fileFieldOptions)
                ->add('category')
                ->add('description', 'textarea', array(
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
            ->add('user', null, array(
                'admin_code' => 'insider_user.admin.user'
            ))
            ->add('link')
            ->add('title')
            ->add('price')
            ->add('priceCurrency')
            ->add('chinaPrice')
            ->add('chinaPriceCurrency')
//            ->add('delivery')
            ->add('quantity')
            ->add('size')
            ->add('color')
            ->add('photo')
            ->add('category')
            ->add('description')
            ->add('isActive')
            ->add('status')
        ;
    }
}
