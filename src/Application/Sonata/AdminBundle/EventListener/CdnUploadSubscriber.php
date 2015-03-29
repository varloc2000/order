<?php

namespace Application\Sonata\AdminBundle\EventListener;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Common\EventArgs;
use Clarity\CdnBundle\Filemanager\Filemanager;
use Application\Sonata\AdminBundle\Entity\CdnUploadableInterface;

/**
 * @author Timofey Cherniavsky <varloc2000@gmail.com>
 */
class CdnUploadSubscriber implements EventSubscriber
{
    /**
     * @var \Clarity\CdnBundle\Filemanager\Filemanager
     */
    protected $fileManager;

    /**
     * @param \Clarity\CdnBundle\Filemanager\Filemanager $fileManager
     */
    public function __construct(Filemanager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        );
    }

    /**
     * @param EventArgs $args
     */
    public function prePersist(EventArgs $args)
    {
        $this->preUpload($args->getEntity());
    }

    /**
     * @param EventArgs $args
     */
    public function preUpdate(EventArgs $args)
    {
        $this->preUpload($args->getEntity());
    }

    /**
     * @param EventArgs $args
     */
    public function postPersist(EventArgs $args)
    {
        $this->upload($args->getEntity());
    }

    /**
     * @param EventArgs $args
     */
    public function postUpdate(EventArgs $args)
    {
        $this->upload($args->getEntity());
    }

    /**
     * @param EventArgs $args
     */
    public function postRemove(EventArgs $args)
    {
        $this->removeUpload($args->getEntity());
    }

    /**
     * @param $object
     */
    protected function preUpload($object)
    {
        if (
            !($object instanceof CdnUploadableInterface)
            || null === $object->getFile()
        ) {
            return;
        }

        $container  = $object->getContainerName();
        $cdn        = null;

        if (false !== strpos($container, '://')) {
            $chunks = explode('://', $container);

            $cdn        = $chunks[0];
            $container  = $chunks[1];
        }

        $uploaded = $this->fileManager->upload(
            $object->getFile(),
            $container,
            $cdn,
            null,
            $this->generateFilename($object->getFile())
        );

        $object->setPath($uploaded->getSchemaPath());
        $object->setFile(null);
    }

    /**
     * @param $object
     */
    protected function upload($object)
    {
        if (
            !($object instanceof CdnUploadableInterface)
        ) {
            return;
        }

        if ($object->getTemp() && $object->getPath()) {
            // delete the old image
            $this->fileManager->remove($object->getTemp());

            // clear the temp image path
            $object->setTemp(null);
        }

        $object->setFile(null);
    }

    /**
     * @param $object
     */
    protected function removeUpload($object)
    {
        if (
            !($object instanceof CdnUploadableInterface)
            || null === $object->getPath()
        ) {
            return;
        }

        $this->fileManager->remove($object->getPath());
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file)
    {
        return sha1(uniqid(mt_rand(), true)) . '.' . $file->guessExtension();
    }
}
