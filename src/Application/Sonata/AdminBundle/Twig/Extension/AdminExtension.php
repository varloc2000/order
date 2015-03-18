<?php
/**
 * Created by Mikhail Pegasin, 04.2014
 */

namespace Application\Sonata\AdminBundle\Twig\Extension;

use AllBY\BaseBundle\Entity\Interfaces\SoftDeleteInterface;

class AdminExtension extends \Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'date' => new \Twig_Filter_Method($this, 'rudate'),
            'is_soft_deletable' => new \Twig_Filter_Method($this, 'isSoftDeletable'),

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

    public function getName()
    {
        return 'itmadmin';
    }

}