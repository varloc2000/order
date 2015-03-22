<?php

namespace Insider\CurrencyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
//use AllBY\BaseBundle\Entity\Interfaces\SoftDeleteInterface;

/**
 * @ORM\Entity(repositoryClass="Insider\CurrencyBundle\Entity\Repository\CurrencyRepository")
 * @ORM\Table(name="currency")
 * @ORM\HasLifecycleCallbacks()
 */
class Currency
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

}
