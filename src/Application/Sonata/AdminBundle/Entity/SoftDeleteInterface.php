<?php

namespace Application\Sonata\AdminBundle\Entity;

interface SoftDeleteInterface
{
    public function setIsActive($isActive);

    public function getIsActive();
}
