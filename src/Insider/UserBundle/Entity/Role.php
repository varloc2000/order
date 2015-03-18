<?php

namespace Insider\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Insider\BaseBundle\Entity\Interfaces\SoftDeleteInterface;

/**
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role
{
    const ROLE_ADMIN        = 0;
    const ROLE_CLIENT       = 1;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AccessByModule", mappedBy="role", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $accessByModules;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isActive = true;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $parentRole = self::ROLE_ADMIN;

    private static $parentRoleNames = array(
        self::ROLE_ADMIN => "Администратор",
        self::ROLE_CLIENT => "Клиент",
    );

    private static $parentRoleKeyNames = array(
        self::ROLE_ADMIN => "ROLE_ADMIN",
        self::ROLE_CLIENT => "ROLE_CLIENT",
    );

    public static function getParentRoleNames()
    {
        return self::$parentRoleNames;
    }

    public static function getParentRoleKeysNames()
    {
        return self::$parentRoleKeyNames;
    }

    public function getParentRoleName()
    {
        return self::$parentRoleNames[$this->parentRole];
    }

    public function getParentRoleKeyName()
    {
        return self::$parentRoleKeyNames[$this->parentRole];
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->accessByModules = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Представление в ввиде строки
     *
     * @return string
     */
    public function __toString()
    {
        return ($this->getName()) ? $this->getName() : 'Новая роль';
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
     * Set name
     *
     * @param string $name
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add accessByModules
     *
     * @param AccessByModule $accessByModules
     * @return Role
     */
    public function addAccessByModule(AccessByModule $accessByModules)
    {
        $this->accessByModules[] = $accessByModules;

        return $this;
    }

    /**
     * Remove accessByModules
     *
     * @param AccessByModule $accessByModules
     */
    public function removeAccessByModule(AccessByModule $accessByModules)
    {
        $this->accessByModules->removeElement($accessByModules);
    }

    /**
     * Get accessByModules
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAccessByModules()
    {
        return $this->accessByModules;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return self
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

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
     * Set parentRole
     *
     * @param integer $parentRole
     * @return Role
     */
    public function setParentRole($parentRole)
    {
        $this->parentRole = $parentRole;

        return $this;
    }

    /**
     * Get parentRole
     *
     * @return integer 
     */
    public function getParentRole()
    {
        return $this->parentRole;
    }
}
