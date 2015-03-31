<?php

namespace Insider\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Insider\CurrencyBundle\Entity\Currency;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Application\Sonata\AdminBundle\Entity\SoftDeleteInterface;

/**
 * @ORM\Entity(repositoryClass="Insider\UserBundle\Entity\Repository\UserRepository")
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="promo", columns={"promo"}) }
 * )
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Такой адрес электронной почты уже существует!")
 */
class User extends BaseUser implements SoftDeleteInterface
{
    const STATUS_REGISTERED = 0;
    const STATUS_CHECKED    = 1;
    const STATUS_BLOCKED    = 2;
    const STATUS_DELETED    = 3;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $city;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $phone;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $skype;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $vk;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $additional;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $promo;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $balance = 0;

    /**
     * @ORM\ManyToOne(targetEntity="Insider\CurrencyBundle\Entity\Currency")
     */
    protected $currency;

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

    /**
     * @ORM\Column(name="avatar_uploaded_at", type="datetime", nullable=true)
     * @var \DateTime
     */
    protected $uploadedAt;

    /**
     * @ORM\OneToMany(targetEntity="Insider\OrderBundle\Entity\Order", mappedBy="user")
     */
    private $orders;

    private static $statusNames = array(
        self::STATUS_REGISTERED => "Зарегистрирован",
        self::STATUS_CHECKED    => "Проверен",
        self::STATUS_BLOCKED    => "Заблокирован",
        self::STATUS_DELETED    => "Удален",
    );

    public static function getStatusNames()
    {
        return self::$statusNames;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setCreatedAt( new \DateTime() );
        $this->status = self::STATUS_REGISTERED;
        $this->enabled = true;
    }

    public function setStatus($status)
    {
        if (!in_array($status, array( self::STATUS_REGISTERED, self::STATUS_CHECKED, self::STATUS_BLOCKED, self::STATUS_DELETED ))) {
            throw new \InvalidArgumentException("Введен не корректный статус");
        }
        $this->status = $status;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setAdminRole()
    {
        if ( !empty($this->role) )
        {
            $this->setRoles(array($this->role->getParentRoleKeyName()));

            foreach( $this->role->getAccessByModules() as $accessByModule )
            {
                $this->addRole(implode("_", array("ROLE",$accessByModule->getModule()->getCode(),$accessByModule->getAccess()->getCode())));
            }
        }
        else
            $this->setRoles(array());

        /*if ( $this->enabled && $this->status == self::STATUS_REGISTERED )
            $this->status = self::STATUS_CHECKED;

        if ( $this->status == self::STATUS_BLOCKED && $this->enabled )
            $this->enabled = false;

        if ( !$this->enabled && $this->status == self::STATUS_CHECKED )
            $this->enabled = true;*/
    }

    /**
     * @return mixed
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param mixed $additional
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * @param mixed $promo
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getVk()
    {
        return $this->vk;
    }

    /**
     * @param mixed $vk
     */
    public function setVk($vk)
    {
        $this->vk = $vk;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return User
     */
    public function setIsActive($isActive)
    {
        if (!$this->isActive && $isActive) {
            $this->setStatus(User::STATUS_BLOCKED);
        }

        $this->isActive = $isActive;

        if (!$this->isActive) {
            $this->setStatus(User::STATUS_DELETED);
        }

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set role
     *
     * @param Role $role
     * @return User
     */
    public function setRole(Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getContainerName()
    {
        return 'user://avatar';
    }

    /**
     * @return array
     */
    public static function getAllowedMimeTypes()
    {
        return array();
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     * @return mixed|void
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        if (null === $file) {
            return;
        }

        // check if we have an old image path
        if (
            isset($this->path)
            && (
                in_array($file->getMimeType(), self::getAllowedMimeTypes())
                || in_array($file->getClientMimeType(), self::getAllowedMimeTypes())
            )
        ) {
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
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
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
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
     * @return $this
     */
    public function setTemp($temp)
    {
        $this->temp = $temp;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUploadedAt()
    {
        return $this->uploadedAt;
    }

    /**
     * @param \DateTime $uploadedAt
     * @return $this
     */
    public function setUploadedAt($uploadedAt)
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
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
     * @return self
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     */
    public function setCurrency(Currency $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return mixed
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * @param mixed $orders
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;
    }
}
