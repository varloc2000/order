<?php

namespace Insider\CurrencyBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Insider\CurrencyBundle\Entity\Currency;

class LoadCurrencyInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadCurrency($manager);

        $manager->flush();
    }

    public function loadCurrency(ObjectManager $manager)
    {
        $dollar = new Currency();
        $dollar->setCourse(1);
        $dollar->setTitle('Доллар');
        $dollar->setSign('USD');
        $dollar->setIsDefault(true);
        $this->setReference('dollar', $dollar);

        $manager->persist($dollar);

        $euro = new Currency();
        $euro->setCourse(1.10);
        $euro->setTitle('Евро');
        $euro->setSign('EU');
        $this->setReference('euro', $dollar);

        $manager->persist($euro);

        $yuan = new Currency();
        $yuan->setCourse(0.16);
        $yuan->setTitle('Юань');
        $yuan->setSign('CNY');
        $this->setReference('yuan', $dollar);

        $manager->persist($yuan);
    }

   /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 0;
    }
}
