<?php

namespace Insider\OrderBundle\Admin;

class DeletedOrderAdmin extends OrderAdmin
{
    protected $baseRouteName = 'deleted';

    protected $baseRoutePattern = 'deleted';

    public function createQuery($context = 'list')
    {
        $query = $this->getModelManager()->createQuery($this->getClass());

        foreach ($this->extensions as $extension) {
            $extension->configureQuery($this, $query, $context);
        }

        $query->andWhere('o.isActive = 0');

        return $query;
    }
}
