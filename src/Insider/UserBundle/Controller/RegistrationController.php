<?php

namespace Insider\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Insider\UserBundle\Entity\User;
use Symfony\Component\Form\FormError;

class RegistrationController extends BaseController
{
    /**
     * @Route("/register/resend", name="resend_confirm")
     * @Template()
     */
    public function resendEmailAction(Request $request)
    {
        $data = array();
        $form = $this->container->get('form.factory')->createBuilder('form', $data)
            ->add("username", "text", array("label" => "Имя пользователя"))
            ->getForm();

        if ( $request->getMethod() == "POST" )
        {
            $form->handleRequest($request);
            // reCaptcha check
            if ( !$form->isValid() ) {
                return array( 'form' => $form->createView() );
            }

            $data = $form->getData();
            $username = $data["username"];
            /** @var $user \FOS\UserBundle\Model\UserInterface|null */
            $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);;

            if (null === $user) {
                // TODO message in translator
                $form->get('username')->addError(new FormError('Пользователь не найден'));
                return array( 'form' => $form->createView() );
            }


            if (null === $user->getConfirmationToken()) {
                // @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }

            $this->container->get('fos_user.mailer')->sendConfirmationEmailMessage($user);
            $this->container->get('fos_user.user_manager')->updateUser($user);

            $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());

            return new RedirectResponse($this->container->get('router')->generate('fos_user_registration_check_email'));
        }

        return array( 'form' => $form->createView() );
    }

    /**
     * {@inheritDoc}
     */
    public function confirmAction(Request $request, $token)
    {
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->findUserByConfirmationToken($token);

        if (null === $user) {
            throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user->setConfirmationToken(null);
        $user->setStatus(User::STATUS_CHECKED);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRM, $event);

        $userManager->updateUser($user);

        if (null === $response = $event->getResponse()) {
            $url = $this->container->get('router')->generate('all_by_frontend_profile_show');
            $response = new RedirectResponse($url);
        }

        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $this->container->get('security.context')->setToken($token);
        $this->container->get('session')->set('_security_main', serialize($token));

        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_CONFIRMED, new FilterUserResponseEvent($user, $request, $response));

        return $response;
    }

    public function registerAction(Request $request)
    {

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);
        $form->get('promo')->setData($request->query->get('promo'));

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_registration_confirmed');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Template()
     */
    public function agreementAction()
    {
        $repo = $this->container->get('doctrine')->getRepository('InsiderUserBundle:Agreement');

        $currentAgreement = $repo->findOneByIsCurrent(true);

        if (!$currentAgreement) {
            throw new NotFoundHttpException('Agreement not found!');
        }

        return array('agreement' => $currentAgreement);
    }
}