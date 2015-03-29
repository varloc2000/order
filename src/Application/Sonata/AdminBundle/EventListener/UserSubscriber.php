<?php

namespace Application\Sonata\AdminBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\Sonata\AdminBundle\Entity\UserInterface AS InsiderUserInterface;

class UserSubscriber implements EventSubscriber
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return array(Events::prePersist, Events::preUpdate);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->assignUser($args->getEntity());
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->assignUser($args->getEntity());
    }

    /**
     * @param $entity
     */
    protected function assignUser($entity)
    {
        if ($entity instanceof InsiderUserInterface) {
            $user = $entity->getUser();

            if (empty($user)) {
                if ($this->container->get('security.context')->getToken()) {
                    $user = $this->container->get('security.context')->getToken()->getUser();
                    $entity->setUser($user);
                }
            }
        }
    }
}
