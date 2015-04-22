<?php

namespace Insider\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Insider\OrderBundle\Entity\Delivery;
use Insider\OrderBundle\Entity\Weight;

class LoadDeliveryInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadDelivery($manager);
        $this->loadWeight($manager);
        $this->loadCustomWeight($manager);

        $manager->flush();
    }

    public function loadDelivery(ObjectManager $manager)
    {
        $delivery = new Delivery();
        $delivery->setTitle('Авиа - до 10 дней');
        $delivery->setPrice(12);
        $delivery->setPriceCurrency($this->getReference('dollar'));
        $delivery->setDescription('Обычно 3-6 дней.');
        $this->setReference('delivery', $delivery);

        $manager->persist($delivery);

        $delivery2 = new Delivery();
        $delivery2->setTitle('Ж\Д / Авто - до 20 дней');
        $delivery2->setPrice(0);
        $delivery2->setPriceCurrency($this->getReference('dollar'));
        $delivery2->setDescription('Обычно 15-25 дней.');
        $this->setReference('delivery1', $delivery);

        $manager->persist($delivery2);

        $delivery3 = new Delivery();
        $delivery3->setTitle('Морем - до 50 дней');
        $delivery3->setPrice(12);
        $delivery3->setPriceCurrency($this->getReference('dollar'));
        $delivery3->setDescription('Обычно 30-40 дней.');
        $this->setReference('delivery2', $delivery);

        $manager->persist($delivery3);
    }

    public function loadWeight(ObjectManager $manager)
    {
        /** weight */
        $weight = new Weight();
        $weight->setMinWeight(1);
        $weight->setMaxWeight(10);
        $weight->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight', $weight);

        $manager->persist($weight);

        /** weight1 */
        $weight1 = new Weight();
        $weight1->setMinWeight(10);
        $weight1->setMaxWeight(50);
        $weight1->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight1', $weight1);

        $manager->persist($weight1);

        /** weight2 */
        $weight2 = new Weight();
        $weight2->setLabel('Выгодно');
        $weight2->setMinWeight(50);
        $weight2->setMaxWeight(100);
        $weight2->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight2', $weight2);

        $manager->persist($weight2);

        /** weight3 */
        $weight3 = new Weight();
        $weight3->setMinWeight(100);
        $weight3->setMaxWeight(250);
        $weight3->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight3', $weight3);

        $manager->persist($weight3);

        /** weight4 */
        $weight4 = new Weight();
        $weight4->setMinWeight(300);
        $weight4->setMaxWeight(500);
        $weight4->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight4', $weight4);

        $manager->persist($weight4);

        /** weight5 */
        $weight5 = new Weight();
        $weight5->setMinWeight(500);
        $weight5->setMaxWeight(1000);
        $weight5->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight5', $weight5);

        $manager->persist($weight5);

        /** weight6 */
        $weight6 = new Weight();
        $weight6->setMinWeight(1000);
        $weight6->setMaxless(true);
        $weight6->addDeliveries($this->getReference('delivery'));
        $this->setReference('weight6', $weight6);

        $manager->persist($weight6);
    }

    /**
     * Load weights for delivery2 (sea delivery)
     * @param ObjectManager $manager
     */
    public function loadCustomWeight(ObjectManager $manager)
    {
        /** weight */
        $weight = new Weight();
        $weight->setType(Weight::TYPE_CUSTOM);
        $weight->setCustom('170 за куб.м.');
        $weight->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom', $weight);

        $manager->persist($weight);

        /** weight1 */
        $weight1 = new Weight();
        $weight1->setType(Weight::TYPE_CUSTOM);
        $weight1->setCustom('160 за куб.м.');
        $weight1->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom1', $weight1);

        $manager->persist($weight1);

        /** weight2 */
        $weight2 = new Weight();
        $weight2->setType(Weight::TYPE_CUSTOM);
        $weight2->setCustom('150 за куб.м.');
        $weight2->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom2', $weight2);

        $manager->persist($weight2);

        /** weight3 */
        $weight3 = new Weight();
        $weight3->setType(Weight::TYPE_CUSTOM);
        $weight3->setCustom('140 за куб.м.');
        $weight3->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom3', $weight3);

        $manager->persist($weight3);

        /** weight4 */
        $weight4 = new Weight();
        $weight4->setType(Weight::TYPE_CUSTOM);
        $weight4->setCustom('125 за куб.м.');
        $weight4->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom4', $weight4);

        $manager->persist($weight4);

        /** weight5 */
        $weight5 = new Weight();
        $weight5->setType(Weight::TYPE_CUSTOM);
        $weight5->setCustom('120 за куб.м.');
        $weight5->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom5', $weight5);

        $manager->persist($weight5);

        /** weight6 */
        $weight6 = new Weight();
        $weight6->setType(Weight::TYPE_CUSTOM);
        $weight6->setCustom('115 за куб.м.');
        $weight6->addDeliveries($this->getReference('delivery2'));
        $this->setReference('weight_custom6', $weight6);

        $manager->persist($weight6);
    }

   /**
    * {@inheritDoc}
    */
    public function getOrder()
    {
        return 1;
    }
}
