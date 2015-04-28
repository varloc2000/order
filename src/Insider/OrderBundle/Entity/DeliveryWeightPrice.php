<?php

namespace Insider\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="delivery_weight")
 * @ORM\HasLifecycleCallbacks()
 */
class DeliveryWeightPrice
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\OrderBundle\Entity\Delivery", inversedBy="weights")
     */
    protected $delivery;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\OrderBundle\Entity\Weight", inversedBy="deliveries")
     */
    protected $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $price = 0;

    /**
     * @param Delivery $delivery
     * @param Weight $weight
     * @param int $price
     */
    public function __construct(Delivery $delivery = null, Weight $weight = null, $price = 0)
    {
        $this->delivery = $delivery;
        $this->weight = $weight;
        $this->price = $price;
    }

    /**
     * @return Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     */
    public function setDelivery(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return Weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param Weight $weight
     */
    public function setWeight(Weight $weight)
    {
        $this->weight = $weight;
    }

    public function __toString()
    {
        return $this->weight->__toString();
    }
}
