<?php

namespace Insider\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Access
 *
 * @ORM\Table(name="access")
 * @ORM\Entity
 */
class Access
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
     * @ORM\OneToMany(targetEntity="AccessByModule", mappedBy="access")
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
     * @return Access
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
     * @return Access
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
        $this->accessByModules = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add accessByModules
     *
     * @param AccessByModule $accessByModules
     * @return Access
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
