<?php

namespace Insider\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Application\Sonata\AdminBundle\Entity\SoftDeleteInterface;
use Application\Sonata\AdminBundle\Entity\UserInterface;
use Insider\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Insider\OrderBundle\Entity\Repository\OrderRepository")
 * @ORM\Table(name="ord")
 * @ORM\HasLifecycleCallbacks()
 */
class Order implements SoftDeleteInterface, UserInterface
{
    const STATUS_NEW = 0;
    const STATUS_OPEN = 1;
    const STATUS_BUYED = 2;
    const STATUS_SENT = 3;
    const STATUS_IN_OFFICE = 4;
    const STATUS_COMPLETE = 5;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="OrderCategory", inversedBy="orders")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\UserBundle\Entity\User", inversedBy="orders")
     */
    protected $user;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $quantity = 1;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $size;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $color;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     */
    protected $priceCurrency;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $chinaPrice;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     */
    protected $chinaPriceCurrency;

    /**
     * @ORM\ManyToOne(targetEntity="Delivery")
     */
    protected $delivery;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isActive = true;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(name="avatar_path", type="string", length=255, nullable=true)
     * @var string
     */
    protected $path;

    /**
     * Store old file path to remove file after update avatar
     * @var string
     */
    private $temp;

    /**
     * @Assert\File(
     *     maxSize = "10000M",
     *     mimeTypes = {"image/jpeg", "image/png", "image/gif"},
     *     mimeTypesMessage = "Недопустимый формат файла"
     * )
     */
    private $file;

    private static $statusNames = array(
        self::STATUS_NEW => "Новый",
        self::STATUS_OPEN => "Открытый (на выкуп)",
        self::STATUS_BUYED => "Выкуплен",
        self::STATUS_SENT => "Отправлен",
        self::STATUS_IN_OFFICE => "Получен (В Минском офисе)",
        self::STATUS_COMPLETE => "Отдан клиенту",
    );

    public static function getStatusNames()
    {
        return self::$statusNames;
    }

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->status = self::STATUS_NEW;
        $this->enabled = true;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getChinaPrice()
    {
        return $this->chinaPrice;
    }

    /**
     * @param mixed $chinaPrice
     */
    public function setChinaPrice($chinaPrice)
    {
        $this->chinaPrice = $chinaPrice;
    }

    /**
     * @return mixed
     */
    public function getChinaPriceCurrency()
    {
        return $this->chinaPriceCurrency;
    }

    /**
     * @param mixed $chinaPriceCurrency
     */
    public function setChinaPriceCurrency($chinaPriceCurrency)
    {
        $this->chinaPriceCurrency = $chinaPriceCurrency;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
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
     * @return mixed
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param mixed $delivery
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;
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
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
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
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
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
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getTemp()
    {
        return $this->temp;
    }

    /**
     * @param string $temp
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;
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
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
    public function setUser(User $user = null)
    {
        $this->user = $user;
    }
}
