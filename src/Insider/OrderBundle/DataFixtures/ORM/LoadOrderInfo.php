<?php

namespace Insider\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Insider\OrderBundle\Entity\Order;

class LoadOrderInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadOrder($manager);

        $manager->flush();
    }

    public function loadOrder(ObjectManager $manager)
    {
        $order = new Order();
        $order->setUser($this->getReference('admin'));
        $order->setTitle('Камера');
        $order->setLink('http://www.aliexpress.com/item/Extra-accessories-Original-SJCAM-SJ5000-Plus-WiFi-Action-HD-Camera-Ambarella-A7LS75-14MP-Waterproof-Sports/32240858143.html');
        $order->setPrice(197.99);
        $order->setPriceCurrency($this->getReference('dollar'));
        $order->setChinaPrice(0);
        $order->setChinaPriceCurrency($this->getReference('dollar'));
        $order->setColor('Желтый');
        $order->setDescription('Полный комплект');
        $order->setDelivery($this->getReference('delivery'));

        $manager->persist($order);

        $order2 = new Order();
        $order2->setUser($this->getReference('client'));
        $order2->setTitle('Тюнер');
        $order2->setLink('http://www.aliexpress.com/item/High-Quality-Digital-LCD-Clip-On-Electronic-Acoustic-Guitar-Tuner-Chromatic-Guitar-Bass-Free-Shipping/1253354153.html?aff_platform=aaf&sk=bm2aeAra%3A27uz33Nj6&cpt=1427142915854&af=6156889&cn=002&cv=11032041&dp=i7mc1imjdq00r310008sc&dl_target_url=http%3A%2F%2Fwww.aliexpress.com%2Fitem%2FHigh-Quality-Digital-LCD-Clip-On-Electronic-Acoustic-Guitar-Tuner-Chromatic-Guitar-Bass-Free-Shipping%2F1253354153.html&PID=6156889&URL=http%3A%2F%2Fwww.aliexpress.com%2Fitem%2FHigh-Quality-Digital-LCD-Clip-On-Electronic-Acoustic-Guitar-Tuner-Chromatic-Guitar-Bass-Free-Shipping%2F1253354153.html');
        $order2->setPrice(7.91);
        $order2->setPriceCurrency($this->getReference('dollar'));
        $order2->setChinaPrice(0);
        $order2->setChinaPriceCurrency($this->getReference('dollar'));
        $order2->setQuantity(2);
        $order2->setDelivery($this->getReference('delivery'));

        $manager->persist($order2);

        $order3 = new Order();
        $order3->setUser($this->getReference('client'));
        $order3->setTitle('Гармонь');
        $order3->setLink('http://www.aliexpress.com/item/High-Quality-Digital-LCD-Clip-On-Electronic-Acoustic-Guitar-Tuner-Chromatic-Guitar-Bass-Free-Shipping/1253354153.html?aff_platform=aaf&sk=bm2aeAra%3A27uz33Nj6&cpt=1427142915854&af=6156889&cn=002&cv=11032041&dp=i7mc1imjdq00r310008sc&dl_target_url=http%3A%2F%2Fwww.aliexpress.com%2Fitem%2FHigh-Quality-Digital-LCD-Clip-On-Electronic-Acoustic-Guitar-Tuner-Chromatic-Guitar-Bass-Free-Shipping%2F1253354153.html&PID=6156889&URL=http%3A%2F%2Fwww.aliexpress.com%2Fitem%2FHigh-Quality-Digital-LCD-Clip-On-Electronic-Acoustic-Guitar-Tuner-Chromatic-Guitar-Bass-Free-Shipping%2F1253354153.html');
        $order3->setPrice(7.91);
        $order3->setPriceCurrency($this->getReference('dollar'));
        $order3->setChinaPrice(0);
        $order3->setChinaPriceCurrency($this->getReference('dollar'));
        $order3->setQuantity(1);
        $order3->setStatus(Order::STATUS_COMPLETE);
        $order3->setDelivery($this->getReference('delivery'));

        $manager->persist($order3);
    }

   /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 5;
    }
}
