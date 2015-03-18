<?php

namespace Insider\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use Application\BaseBundle\Entity\Interfaces\SoftDeleteInterface;

/**
 * AccessByModule
 *
 * @ORM\Table(name="access_by_module")
 * @ORM\Entity
 */
class AccessByModule
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
     * @var Role
     *
     * @ORM\ManyToOne(targetEntity="Role", inversedBy="accessByModules", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;

    /**
     * @var Module
     *
     * @ORM\ManyToOne(targetEntity="Module", inversedBy="accessByModules", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="module_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $module;

    /**
     * @var Access
     *
     * @ORM\ManyToOne(targetEntity="Access", inversedBy="accessByModules", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="access_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $access;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $isActive = true;

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
     * Set role
     *
     * @param Role $role
     * @return AccessByModule
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
     * Set module
     *
     * @param Module $module
     * @return AccessByModule
     */
    public function setModule(Module $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set access
     *
     * @param Access $access
     * @return AccessByModule
     */
    public function setAccess(Access $access = null)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return Access
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Представление в ввиде строки
     *
     * @return string
     */
    public function __toString()
    {
        return $this->module->getName().":".$this->access->getName();
    }
}
