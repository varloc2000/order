<?php

namespace Application\Sonata\AdminBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @author Timofey Cherniavsky <varloc2000@gmail.com>
 */
interface CdnUploadableInterface
{
    /**
     * @return array
     */
    public static function getAllowedMimeTypes();

    /**
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile|null
     */
    public function getFile();

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return mixed
     */
    public function setFile(UploadedFile $file = null);

    /**
     * @return string
     */
    public function getPath();

    /**
     * @param string $path
     */
    public function setPath($path);

    /**
     * @return string
     */
    public function getTemp();

    /**
     * @param string $temp
     */
    public function setTemp($temp);

    /**
     * @return string - container to use in cdn path
     * Can use string like 'cdn_name://container_name' to specify cdn storage besides container
     */
    public function getContainerName();
}
