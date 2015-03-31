<?php

namespace Insider\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Insider\OrderBundle\Entity\Delivery;

class LoadDeliveryInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadDelivery($manager);

        $manager->flush();
    }

    public function loadDelivery(ObjectManager $manager)
    {
        $delivery = new Delivery();
        $delivery->setTitle('Авиа');
        $delivery->setPrice(12);
        $delivery->setPriceCurrency($this->getReference('dollar'));
        $delivery->setDescription('Быстрая');

        $manager->persist($delivery);

        $delivery2 = new Delivery();
        $delivery2->setTitle('ЖД');
        $delivery2->setPrice(0);
        $delivery2->setPriceCurrency($this->getReference('dollar'));
        $delivery2->setDescription('Медленная');

        $manager->persist($delivery2);

        $delivery3 = new Delivery();
        $delivery3->setTitle('По морю');
        $delivery3->setPrice(12);
        $delivery3->setPriceCurrency($this->getReference('dollar'));
        $delivery3->setDescription('Так себе');

        $manager->persist($delivery3);
    }

   /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 1;
    }
}
