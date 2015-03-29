<?php

namespace Application\Sonata\AdminBundle\Twig\Extension;

use Application\Sonata\AdminBundle\Entity\SoftDeleteInterface;
use Insider\OrderBundle\Entity\Order;

class AdminExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
//    public function getFunctions()
//    {
//        return array(
//            'static' => new \Twig_SimpleFunction($this, 'getStatic')
//        );
//    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'date' => new \Twig_Filter_Method($this, 'rudate'),
            'is_soft_deletable' => new \Twig_Filter_Method($this, 'isSoftDeletable'),
            'orderStatusLabel' => new \Twig_Filter_Method($this, 'getOrderStatusLabel'),

        );
    }

    public function rudate($dateTime)
    {
        setlocale(LC_TIME, "ru_RU.UTF-8");
        if ($dateTime instanceof \DateTime) {
            return strftime("%d %B, %Y %H:%M ", $dateTime->getTimestamp());
        } else {
            $dateTime = strtotime($dateTime);
            return strftime("%d.%m.%Y %H:%M:%S ", $dateTime);
        }
    }

    /**
     * @param $object
     * @return bool
     */
    public function isSoftDeletable($object)
    {
        return ($object instanceof SoftDeleteInterface);
    }

    public function getOrderStatusLabel($status)
    {
        return Order::getStatusNames()[$status];
    }

    public function getName()
    {
        return 'itmadmin';
    }
}
