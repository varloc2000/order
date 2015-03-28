<?php

namespace Insider\OrderBundle\Admin;

use Insider\OrderBundle\Entity\Order;

class CompletedOrderAdmin extends OrderAdmin
{
    protected $baseRouteName = 'completed';

    protected $baseRoutePattern = 'completed';

    public function createQuery($context = 'list')
    {
        $query = $this->getModelManager()->createQuery($this->getClass());

        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        }

        $query
            ->andWhere('o.isActive = 1')
            ->andWhere('o.status = :status')
            ->setParameter('status', Order::STATUS_COMPLETE)
        ;

        return $query;
    }
}
