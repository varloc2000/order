<?php

namespace Insider\CurrencyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="tariff")
 * @ORM\HasLifecycleCallbacks()
 */
class Tariff
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
     * @ORM\Column(type="float", nullable=false)
     */
    protected $commission = 0;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $priceFirst;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     * @var \Insider\CurrencyBundle\Entity\Currency
     */
    protected $priceFirstCurrency;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $priceSecond;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     * @var \Insider\CurrencyBundle\Entity\Currency
     */
    protected $priceSecondCurrency;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isDefault = false;

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
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param mixed $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return mixed
     */
    public function getPriceFirst()
    {
        return $this->priceFirst;
    }

    /**
     * @param mixed $priceFirst
     */
    public function setPriceFirst($priceFirst)
    {
        $this->priceFirst = $priceFirst;
    }

    /**
     * @return mixed
     */
    public function getPriceFirstCurrency()
    {
        return $this->priceFirstCurrency;
    }

    /**
     * @param mixed $priceFirstCurrency
     */
    public function setPriceFirstCurrency($priceFirstCurrency)
    {
        $this->priceFirstCurrency = $priceFirstCurrency;
    }

    /**
     * @return mixed
     */
    public function getPriceSecond()
    {
        return $this->priceSecond;
    }

    /**
     * @param mixed $priceSecond
     */
    public function setPriceSecond($priceSecond)
    {
        $this->priceSecond = $priceSecond;
    }

    /**
     * @return Currency
     */
    public function getPriceSecondCurrency()
    {
        return $this->priceSecondCurrency;
    }

    /**
     * @param Currency $priceSecondCurrency
     */
    public function setPriceSecondCurrency($priceSecondCurrency)
    {
        $this->priceSecondCurrency = $priceSecondCurrency;
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
     * @return mixed
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * @param mixed $commission
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;
    }
}
