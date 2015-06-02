<?php

namespace Insider\UserBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Insider\UserBundle\Entity\Role;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;

/**
 * Listener responsible for adding the default user role at registration
 */
class RegistrationListener implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $roleRepo = $this->entityManager->getRepository('InsiderUserBundle:Role');
        $currencyRepo = $this->entityManager->getRepository('InsiderCurrencyBundle:Currency');

        $roleNames = Role::getParentRoleNames();
        $clientRole = $roleRepo->findOneByName($roleNames[Role::ROLE_CLIENT]);

        $defaultCurrency = $currencyRepo->findOneByIsDefault(1);

        /** @var $user \FOS\UserBundle\Model\UserInterface */
        $user = $event->getForm()->getData();
        $user->setRole($clientRole);
        $user->setCurrency($defaultCurrency);
    }
}
