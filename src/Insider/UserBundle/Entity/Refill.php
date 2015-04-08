<?php

namespace Insider\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Insider\CurrencyBundle\Entity\Currency;

/**
 * @ORM\Table(name="refill")
 * @ORM\Entity
 */
class Refill
{
    const TYPE_CHINA = 0;
    const TYPE_CASH = 1;
    const TYPE_CLEARING = 2;
    const TYPE_ERROR = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $comment;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $amount;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     * @var \Insider\CurrencyBundle\Entity\Currency
     */
    protected $currency;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\UserBundle\Entity\User")
     * @var \Insider\UserBundle\Entity\User
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\UserBundle\Entity\User")
     * @var \Insider\UserBundle\Entity\User
     */
    protected $author;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    private static $typeNames = array(
        self::TYPE_CHINA => "Китай",
        self::TYPE_CASH => "Наличные",
        self::TYPE_CLEARING => "Безналичные",
        self::TYPE_ERROR => "Ошибка",
    );

    public static function getStatusNames()
    {
        return self::$typeNames;
    }

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->type = self::TYPE_CLEARING;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \Insider\CurrencyBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param \Insider\CurrencyBundle\Entity\Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;
    }

    public function __toString()
    {
        if ($this->amount) {
            return $this->amount;
        }

        return 'Новое пополнение';
    }
}
