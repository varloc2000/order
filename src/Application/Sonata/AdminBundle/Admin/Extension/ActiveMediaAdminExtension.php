<?php

namespace Application\Sonata\AdminBundle\Admin\Extension;

use AllBY\BaseBundle\Entity\Interfaces\MediaItemInterface;
use Sonata\AdminBundle\Admin\AdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

/**
 * Not registered as service admin extension
 *
 * Class ActiveMediaAdminExtension
 * @package Application\Sonata\AdminBundle\Admin\Extension
 */
class ActiveMediaAdminExtension extends AdminExtension
{
    /**
     * Add where statement to query to filter not finished media
     * {@inheritdoc}
     */
    public function configureQuery(AdminInterface $admin, ProxyQueryInterface $query, $context = 'list')
    {
        $className = $admin->getClass();
        $class = false !== $admin->getSubject()
            ? $admin->getSubject()
            : new $className;

        if ($class instanceof MediaItemInterface) {
            $query->getQueryBuilder()
                ->andWhere('o.isFinished = true')
            ;
        }
    }
}