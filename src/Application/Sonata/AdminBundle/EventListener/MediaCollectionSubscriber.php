<?php

namespace Application\Sonata\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;
use AllBY\BaseBundle\Entity\Interfaces\MediaItemInterface;

/**
 * @author Timofey Cherniavsky <varloc2000@gmail.com>
 */
class MediaCollectionSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
        ];
    }

    /**
     * @param EventArgs $args
     */
    public function preUpdate(EventArgs $args)
    {
        $object = $args->getEntity();

        if (
            $object instanceof MediaItemInterface
            && !$object->getIsCollectionUpdate()
            && false === $object->getIsFinished()
        ) {
            $object->setIsFinished(true);

            // Fix accordingly with doctrine behavior: http://docs.doctrine-project.org/en/2.1/reference/events.html#onflush
            $em = $args->getEntityManager();
            $uow = $em->getUnitOfWork();
            $meta = $em->getClassMetadata(get_class($object));
            $uow->recomputeSingleEntityChangeSet($meta, $object);
        }
    }
}
