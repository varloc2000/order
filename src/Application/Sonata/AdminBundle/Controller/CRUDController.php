<?php

namespace Application\Sonata\AdminBundle\Controller;

use Insider\OrderBundle\Entity\Order;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\AdminBundle\Exception\ModelManagerException;

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
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        if (false === $this->admin->isGranted('RECOVER', $object)) {
            throw new AccessDeniedException();
        }

        if ($this->getRestMethod() == 'PUT' && method_exists($object, 'setIsActive')) {
            // check the csrf token
            $this->validateCsrfToken('sonata.recover');

            try {
                $object->setIsActive(true);
                $this->admin->preUpdate($object);
                $doctrine = $this->getDoctrine();
                $em = $doctrine->getManager();
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

            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        $this->admin->setTemplate('recover', 'SonataAdminBundle:CRUD:recover.html.twig');

        return $this->render($this->admin->getTemplate('recover'), array(
            'object'     => $object,
            'action'     => 'recover',
            'csrf_token' => $this->getCsrfToken('sonata.recover')
        ));
    }

    public function changeOrderStatusAction($id, $status)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if (!$object instanceof Order) {
            throw new \LogicException('Change status action available only for Order instance');
        }

        $object->setStatus($status);

        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $em->persist($object);
        $em->flush();

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
