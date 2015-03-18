<?php

namespace Insider\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Модуль в системе администрирования
 *
 * @ORM\Table(name="module")
 * @ORM\Entity
 */
class Module
{
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
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="AccessByModule", mappedBy="module")
     */
    private $accessByModules;
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
     * @return Module
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
     * Set code
     *
     * @param string $code
     * @return Module
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Представление в ввиде строки
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add roles
     *
     * @param Role $roles
     * @return Module
     */
    public function addRole(Role $roles)
    {
        $this->roles[] = $roles;

        return $this;
    }

    /**
     * Remove roles
     *
     * @param Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add accessByModules
     *
     * @param AccessByModule $accessByModules
     * @return Module
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
}
