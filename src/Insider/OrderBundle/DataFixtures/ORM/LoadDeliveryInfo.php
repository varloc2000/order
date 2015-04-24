<?php

namespace Insider\OrderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Insider\OrderBundle\Entity\Delivery;
use Insider\OrderBundle\Entity\DeliveryWeightPrice;
use Insider\OrderBundle\Entity\Weight;

class LoadDeliveryInfoData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadWeight($manager);
        $this->loadCustomWeight($manager);
        $this->loadDelivery($manager);
        $manager->flush();

        $this->loadDeliveryWeightPrices($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadDeliveryWeightPrices(ObjectManager $manager)
    {
        $weights = array(
            $this->getReference('weight'),
            $this->getReference('weight1'),
            $this->getReference('weight2'),
            $this->getReference('weight3'),
            $this->getReference('weight4'),
            $this->getReference('weight5'),
            $this->getReference('weight6'),
        );

        $customWeights = array(
            $this->getReference('weight_custom'),
            $this->getReference('weight_custom1'),
            $this->getReference('weight_custom2'),
            $this->getReference('weight_custom3'),
            $this->getReference('weight_custom4'),
            $this->getReference('weight_custom5'),
            $this->getReference('weight_custom6'),
        );

        /** @var Weight $weight */
        foreach ($weights as $weight) {
            $manager->persist(
                $this->createDeliveryWeightPriceByWeight($this->getReference('delivery'), $weight)
            );

            $manager->persist(
                $this->createDeliveryWeightPriceByWeight($this->getReference('delivery1'), $weight)
            );
        }

        /** @var Weight $weight */
        foreach ($customWeights as $weight) {
            $manager->persist(
                $this->createDeliveryWeightPriceByWeight($this->getReference('delivery2'), $weight)
            );
        }
    }

    public function loadDelivery(ObjectManager $manager)
    {
        $delivery = new Delivery();
        $delivery->setTitle('Авиа - до 10 дней');
        $delivery->setPriceCurrency($this->getReference('dollar'));
        $delivery->setDescription('Обычно 3-6 дней.');
        $this->setReference('delivery', $delivery);

        $manager->persist($delivery);

        $delivery2 = new Delivery();
        $delivery2->setTitle('Ж\Д / Авто - до 20 дней');
        $delivery2->setPriceCurrency($this->getReference('dollar'));
        $delivery2->setDescription('Обычно 15-25 дней.');
        $this->setReference('delivery1', $delivery2);

        $manager->persist($delivery2);

        $delivery3 = new Delivery();
        $delivery3->setTitle('Морем - до 50 дней');
        $delivery3->setPriceCurrency($this->getReference('dollar'));
        $delivery3->setDescription('Обычно 30-40 дней.');
        $this->setReference('delivery2', $delivery3);

        $manager->persist($delivery3);
    }

    /**
     * @param Delivery $delivery
     * @param Weight $weight
     * @return DeliveryWeightPrice
     */
    private function createDeliveryWeightPriceByWeight(Delivery $delivery, Weight $weight)
    {
        return new DeliveryWeightPrice(
            $delivery,
            $weight,
            10
        );
    }

    public function loadWeight(ObjectManager $manager)
    {
        /** weight */
        $weight = new Weight();
        $weight->setMinWeight(1);
        $weight->setMaxWeight(10);
        $this->setReference('weight', $weight);

        $manager->persist($weight);

        /** weight1 */
        $weight1 = new Weight();
        $weight1->setMinWeight(10);
        $weight1->setMaxWeight(50);
        $this->setReference('weight1', $weight1);

        $manager->persist($weight1);

        /** weight2 */
        $weight2 = new Weight();
        $weight2->setLabel('Выгодно');
        $weight2->setMinWeight(50);
        $weight2->setMaxWeight(100);
        $this->setReference('weight2', $weight2);

        $manager->persist($weight2);

        /** weight3 */
        $weight3 = new Weight();
        $weight3->setMinWeight(100);
        $weight3->setMaxWeight(250);
        $this->setReference('weight3', $weight3);

        $manager->persist($weight3);

        /** weight4 */
        $weight4 = new Weight();
        $weight4->setMinWeight(300);
        $weight4->setMaxWeight(500);
        $this->setReference('weight4', $weight4);

        $manager->persist($weight4);

        /** weight5 */
        $weight5 = new Weight();
        $weight5->setMinWeight(500);
        $weight5->setMaxWeight(1000);
        $this->setReference('weight5', $weight5);

        $manager->persist($weight5);

        /** weight6 */
        $weight6 = new Weight();
        $weight6->setMinWeight(1000);
        $weight6->setMaxless(true);
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
        $this->setReference('weight_custom', $weight);

        $manager->persist($weight);

        /** weight1 */
        $weight1 = new Weight();
        $weight1->setType(Weight::TYPE_CUSTOM);
        $weight1->setCustom('160 за куб.м.');
        $this->setReference('weight_custom1', $weight1);

        $manager->persist($weight1);

        /** weight2 */
        $weight2 = new Weight();
        $weight2->setType(Weight::TYPE_CUSTOM);
        $weight2->setCustom('150 за куб.м.');
        $this->setReference('weight_custom2', $weight2);

        $manager->persist($weight2);

        /** weight3 */
        $weight3 = new Weight();
        $weight3->setType(Weight::TYPE_CUSTOM);
        $weight3->setCustom('140 за куб.м.');
        $this->setReference('weight_custom3', $weight3);

        $manager->persist($weight3);

        /** weight4 */
        $weight4 = new Weight();
        $weight4->setType(Weight::TYPE_CUSTOM);
        $weight4->setCustom('125 за куб.м.');
        $this->setReference('weight_custom4', $weight4);

        $manager->persist($weight4);

        /** weight5 */
        $weight5 = new Weight();
        $weight5->setType(Weight::TYPE_CUSTOM);
        $weight5->setCustom('120 за куб.м.');
        $this->setReference('weight_custom5', $weight5);

        $manager->persist($weight5);

        /** weight6 */
        $weight6 = new Weight();
        $weight6->setType(Weight::TYPE_CUSTOM);
        $weight6->setCustom('115 за куб.м.');
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
