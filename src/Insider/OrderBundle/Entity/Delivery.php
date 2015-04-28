<?php

namespace Insider\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="delivery")
 * @ORM\HasLifecycleCallbacks()
 */
class Delivery
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     */
    protected $priceCurrency;

    /**
     * @ORM\OneToMany(targetEntity="Insider\OrderBundle\Entity\DeliveryWeightPrice", mappedBy="delivery", indexBy="delivery", cascade={"persist", "remove"})
     */
    protected $weights;

    public function __construct()
    {
        $this->weights = new ArrayCollection();
    }

    public function getPriceByWeight($orderWeight)
    {
        /** @var DeliveryWeightPrice $deliveryWeightPrice */
        foreach ($this->weights as $deliveryWeightPrice) {
            $weight = $deliveryWeightPrice->getWeight();
            // For custom weights cannot decide price by order weight
            if ($weight->isCustom()) {
                continue;
            }

            if (
                ($weight->getMinless()
                    || (!$weight->getMinless() && $weight->getMinWeight() <= $orderWeight)
                )
                && ($weight->getMaxless()
                    || (!$weight->getMaxless() && $weight->getMaxWeight() > $orderWeight)
                )
            ) {
                return $deliveryWeightPrice->getPrice();
            }
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * @param mixed $priceCurrency
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return ArrayCollection
     */
    public function getWeights()
    {
        return $this->weights;
    }

    /**
     * @param ArrayCollection $weights
     */
    public function setWeights(ArrayCollection $weights)
    {
        $this->weights = $weights;
    }

    /**
     * @param Weight $weight
     */
    public function addWeights(DeliveryWeightPrice $weight)
    {
        $this->weights->add($weight);
    }

    public function __toString()
    {
        return $this->title
            ? $this->title
            : 'Новая доставка'
        ;
    }
}
