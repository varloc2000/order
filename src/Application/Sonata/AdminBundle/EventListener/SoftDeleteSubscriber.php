<?php

namespace Application\Sonata\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;
use Application\Sonata\AdminBundle\Entity\SoftDeleteInterface;

class SoftDeleteSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(Events::onFlush);
    }

    public function onFlush(EventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityDeletions() as $entity)
        {
            if ($entity instanceof SoftDeleteInterface && true === $entity->getIsActive()) {
                $entity->setIsActive(false);
                $em->persist($entity);
                $em->flush($entity);
            }
        }
    }
}
