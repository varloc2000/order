<?php

namespace Insider\OrderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="weight")
 * @ORM\HasLifecycleCallbacks()
 */
class Weight
{
    const TYPE_KILO = 0;
    const TYPE_CUSTOM = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Insider\OrderBundle\Entity\Delivery")
     */
    protected $deliveries;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $label;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $type = self::TYPE_KILO;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $minWeight;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $minless = false;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $maxWeight;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $maxless = false;

    /**
     * Need to fill if weight $this->type is self::TYPE_CUSTOM
     * @ORM\Column(type="string", nullable=true)
     */
    protected $custom;

    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getDeliveries()
    {
        return $this->deliveries;
    }

    /**
     * @param ArrayCollection $deliveries
     */
    public function setDeliveries(ArrayCollection $deliveries)
    {
        $this->deliveries = $deliveries;
    }

    /**
     * @param Delivery $delivery
     */
    public function addDeliveries(Delivery $delivery)
    {
        $this->deliveries->add($delivery);
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * @param mixed $maxWeight
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = $maxWeight;
    }

    /**
     * @return mixed
     */
    public function getMaxless()
    {
        return $this->maxless;
    }

    /**
     * @param mixed $maxless
     */
    public function setMaxless($maxless)
    {
        $this->maxless = $maxless;
    }

    /**
     * @return mixed
     */
    public function getMinWeight()
    {
        return $this->minWeight;
    }

    /**
     * @param mixed $minWeight
     */
    public function setMinWeight($minWeight)
    {
        $this->minWeight = $minWeight;
    }

    /**
     * @return mixed
     */
    public function getMinless()
    {
        return $this->minless;
    }

    /**
     * @param mixed $minless
     */
    public function setMinless($minless)
    {
        $this->minless = $minless;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->type
            ? (string) $this->type
            : 'Новый вес'
        ;
    }
}
