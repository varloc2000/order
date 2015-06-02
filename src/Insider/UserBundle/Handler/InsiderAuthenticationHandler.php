<?php

namespace Insider\UserBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Insider\UserBundle\Exception\RegistrationException;

class InsiderAuthenticationHandler implements AuthenticationFailureHandlerInterface, AuthenticationSuccessHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($exception instanceof DisabledException) {

            return new RedirectResponse($this->container->get('router')->generate('resend_confirm'));
        } else if ($exception instanceof RegistrationException) {
            $user = $exception->getUser();
            $token = new UsernamePasswordToken($user->getUsername(), $user->getPassword(), 'main', $user->getRoles());
            $this->container->get('security.context')->setToken($token);
            $this->container->get('session')->set('_security_main',serialize($token));

            return new RedirectResponse($this->container->get('router')->generate('fos_user_registration_register'));
        }

        $request->getSession()->set(SecurityContextInterface::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse($request->headers->get('referer'));
    }

    /**
     * {@inheritDoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return new RedirectResponse($this->container->get('router')->generate('sonata_admin_dashboard'));
    }
}
