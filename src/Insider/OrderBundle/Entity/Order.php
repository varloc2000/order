<?php

namespace Insider\OrderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Insider\CurrencyBundle\Entity\Currency;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Application\Sonata\AdminBundle\Entity\SoftDeleteInterface;
use Application\Sonata\AdminBundle\Entity\UserInterface;
use Application\Sonata\AdminBundle\Entity\CdnUploadableInterface;
use Insider\UserBundle\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Insider\OrderBundle\Entity\Repository\OrderRepository")
 * @ORM\Table(name="ord")
 * @ORM\HasLifecycleCallbacks()
 */
class Order implements SoftDeleteInterface, UserInterface, CdnUploadableInterface
{
    const STATUS_NEW = 0;
    const STATUS_OPEN = 1;
    const STATUS_BUYED = 2;
    const STATUS_SENT = 3;
    const STATUS_IN_OFFICE = 4;
    const STATUS_COMPLETE = 5;

    const PHOTO_TYPE_ONE = 'image/gif';
    const PHOTO_TYPE_SECOND = 'image/jpeg';
    const PHOTO_TYPE_THIRD = 'image/pjpeg';
    const PHOTO_TYPE_FOURTH = 'image/png';
    const PHOTO_TYPE_FIFTH = 'image/svg+xml';
    const PHOTO_TYPE_SIXTH = 'image/tiff';
    const PHOTO_TYPE_SEVENTH = 'image/vnd.microsoft.icon';
    const PHOTO_TYPE_EIGHTH = 'image/vnd.wap.wbmp';
    const PHOTO_TYPE_NINTH = 'application/octet-stream';

    /**
     * @var array
     */
    static $allowedMimeTypes = array(
        self::PHOTO_TYPE_ONE,
        self::PHOTO_TYPE_SECOND,
        self::PHOTO_TYPE_THIRD,
        self::PHOTO_TYPE_FOURTH,
        self::PHOTO_TYPE_FIFTH,
        self::PHOTO_TYPE_SIXTH,
        self::PHOTO_TYPE_SEVENTH,
        self::PHOTO_TYPE_EIGHTH,
        self::PHOTO_TYPE_NINTH,
    );

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
     * @Assert\NotBlank()
     */
    protected $link;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank()
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
     * @Assert\NotBlank()
     */
    protected $price;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     * @Assert\NotNull()
     */
    protected $priceCurrency;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank()
     */
    protected $chinaPrice;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     * @Assert\NotNull()
     */
    protected $chinaPriceCurrency;

    /**
     * @ORM\ManyToOne(targetEntity="Delivery")
     * @Assert\NotNull()
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
     * Return next status number or null if order is completed
     * @return int|null
     */
    public function getNextStatus()
    {
        return array_key_exists($this->status + 1, self::$statusNames)
            ? $this->status + 1
            : null
        ;
    }

    /**
     * Return previous status number or null if order is new
     * @return int|null
     */
    public function getPrevStatus()
    {
        return array_key_exists($this->status - 1, self::$statusNames)
            ? $this->status - 1
            : null
        ;
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
     * @return mixed
     */
    public function getChinaPriceInOrderCurrency()
    {
        return round($this->chinaPrice / $this->chinaPriceCurrency->getCourse(), 2);
    }

    /**
     * @return mixed
     */
    public function getChinaPriceByCurrency(Currency $currency)
    {
        return round($this->chinaPrice / $currency->getCourse(), 2);
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
     * {@inheritDoc}
     */
    public function setFile(UploadedFile $file = null)
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
     * @return mixed
     */
    public function getPriceInOrderCurrency()
    {
        return round($this->price / $this->priceCurrency->getCourse(), 2);
    }

    /**
     * @return mixed
     */
    public function getPriceByCurrency(Currency $currency)
    {
        return round($this->price / $currency->getCourse(), 2);
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

    public function __toString()
    {
        return $this->title
            ? $this->title
            : 'Новый заказ'
        ;
    }

    /**
     * @return array
     */
    public static function getAllowedMimeTypes()
    {
        return self::$allowedMimeTypes;
    }

    /**
     * @return string - container to use in cdn path
     * Can use string like 'cdn_name://container_name' to specify cdn storage besides container
     */
    public function getContainerName()
    {
        return 'orderphoto';
    }
}
