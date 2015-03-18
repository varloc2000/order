<?php

namespace Application\Sonata\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\AdminBundle\Exception\ModelManagerException;

use AllBY\BaseBundle\Entity\Interfaces\SoftDeleteInterface;
use AllBY\BaseBundle\Entity\Interfaces\MediaItemInterface;
use Application\Sonata\AdminBundle\Form\Type\MediaCollectionType;

class CRUDController extends Controller
{
    /**
     * @param $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function recoverAction($id)
    {
        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('RESTORE', $object)) {
            throw new AccessDeniedException();
        }

        if ($this->getRestMethod() == 'PUT' && method_exists($object, 'setIsActive')) {
            // check the csrf token
            $this->validateCsrfToken('sonata.recover');

            try {
                $object->setIsActive(true);
                $this->admin->preUpdate($object);
                $doctrine = $this->admin->getConfigurationPool()->getContainer()->get('doctrine');
                $em = $doctrine->getEntityManager();
                $em->persist($object);
                $em->flush();

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'ok'));
                }

                $this->addFlash(
                    'sonata_flash_success',
                    $this->admin->trans(
                        'flash_recover_success',
                        array('%name%' => $this->admin->toString($object)),
                        'SonataAdminBundle'
                    )
                );

            } catch (ModelManagerException $e) {

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array('result' => 'error'));
                }

                $this->addFlash(
                    'sonata_flash_error',
                    $this->admin->trans(
                        'flash_recover_error',
                        array('%name%' => $this->admin->toString($object)),
                        'SonataAdminBundle'
                    )
                );
            }

            if ( $object instanceof \AllBY\WikiBundle\Entity\Category )
                return new RedirectResponse($this->admin->generateUrl('tree'));

            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        $this->admin->setTemplate('recover', 'SonataAdminBundle:CRUD:recover.html.twig');

        return $this->render($this->admin->getTemplate('recover'), array(
            'object'     => $object,
            'action'     => 'recover',
            'csrf_token' => $this->getCsrfToken('sonata.recover')
        ));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \RuntimeException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @throws \Exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createManyAction(Request $request)
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $form = $this->getCreateManyForm(array('submit'));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                try {

                    // Process each collection item
                    foreach ($form->get('media_collection') as $mediaForm) {

                        $mediaObject = $mediaForm->getData();

                        if (!$mediaObject instanceof MediaItemInterface) {
                            throw new \RuntimeException('Media collection item form data class must implement "\AllBY\BaseBundle\Entity\Interfaces\MediaItemInterface"');
                        }

                        $existedMediaObject = $this->admin->getObject($mediaObject->getId());

                        if (null === $existedMediaObject) {
                            throw new \RuntimeException(sprintf(
                                '"%s" with id "%s" not found!',
                                $this->admin->getClass(),
                                $mediaObject->getId()
                            ));
                        }

                        $existedMediaObject->setLanguage($mediaObject->getLanguage());

                        $existedMediaObject->setIsCollectionUpdate(true);
                        $this->admin->update($existedMediaObject);

                        $this->addFlash('sonata_flash_success', $this->admin->trans(
                            'flash_create_success',
                            array(),
                            'SonataAdminBundle'
                        ));
                    }

                    // redirect to edit mode
                    return new RedirectResponse(
                        $this->admin->generateUrl('list', array('filter' => $this->admin->getFilterParameters()))
                    );
                } catch (\Exception $e) {
                    $this->addFlash('sonata_flash_error', $this->admin->trans(
                        'flash_create_error',
                        array(),
                        'SonataAdminBundle'
                    ));
                }
            } else {
                $this->addFlash('sonata_flash_error', $this->admin->trans(
                    'flash_create_error',
                    array(),
                    'SonataAdminBundle'
                ));
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render('SonataAdminBundle:CRUD:create_many.html.twig', array(
            'object' => null,
            'action' => 'create',
            'form'   => $view,
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \RuntimeException
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function uploadMediaAction(Request $request)
    {
        if (false === $this->admin->isGranted('CREATE') || false === $request->isXmlHttpRequest()) {
            throw new AccessDeniedException();
        }

        $form = $this->getCreateManyForm(array('upload'));

        $form->handleRequest($request);

        $response = new JsonResponse();

        foreach ($form->get('media_collection') as $mediaForm) {

            $mediaObject = $mediaForm->getData();

            if (!$mediaObject instanceof MediaItemInterface) {
                throw new \RuntimeException('Media collection item form data class must implement "\AllBY\BaseBundle\Entity\Interfaces\MediaItemInterface"');
            }

            if (!$mediaObject instanceof SoftDeleteInterface) {
                throw new \RuntimeException('Media collection item must implement "\AllBY\BaseBundle\Entity\Interfaces\SoftDeleteInterface"');
            }

            $uploadedFile = $mediaObject->getFile();

            if ($uploadedFile instanceof UploadedFile) {

                if ($mediaForm->isValid()) {

                    try {
                        if (null !== $mediaObject->getId()) {
                            $existedMediaObject = $this->admin->getObject($mediaObject->getId());

                            if (null === $existedMediaObject) {
                                throw new \RuntimeException(sprintf(
                                    '"%s" with id "%s" not found!',
                                    $this->admin->getClass(),
                                    $mediaObject->getId()
                                ));
                            }

                            $existedMediaObject->setLanguage($mediaObject->getLanguage());
                            $existedMediaObject->setFile($uploadedFile);

                            $this->admin->update($existedMediaObject);
                        } else {
                            $mediaObject->setIsFinished(false);
                            $mediaObject->setTitle($this->admin->trans(
                                'media_collection_tmp_title',
                                array(),
                                'SonataAdminBundle'
                            ));

                            $mediaObject->setIsCollectionUpdate(true);
                            $this->admin->create($mediaObject);
                        }

                        return $response->setData(array(
                            'file' => array(
                                'id' => $mediaObject->getId(),
                                'size' => $uploadedFile->getClientSize(),
                            ),
                        ));
                    } catch (\Exception $e) {
                        return $response->setData(array(
                            'file' => array(
                                'errors' => $e->getMessage(),
                            ),
                        ));
                    }
                } else {
                    return $response->setData(array(
                        'file' => array(
                            'errors' => $mediaForm->get('file')->getErrorsAsString(),
                        ),
                    ));
                }
            }
        }

        return $response->setData(array(
            'file' => array(
                'errors' => 'No one file was selected',
            )
        ));
    }

    /**
     * @param array $validationGroups
     * @return \Symfony\Component\Form\Form
     */
    protected function getCreateManyForm(array $validationGroups = array())
    {
        $className = $this->admin->getClass();

        return $this->createForm(new MediaCollectionType(), null, array(
            'action' => $this->admin->generateUrl('create_many'),
            'media_data_class' => $className,
            'media_mime_types' => $className::getAllowedMimeTypes(),
            'validation_groups' => $validationGroups,
            'admin' => $this->admin,
        ));
    }
}
