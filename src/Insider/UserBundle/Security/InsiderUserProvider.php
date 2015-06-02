<?php

namespace Insider\UserBundle\Security;

use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use Insider\UserBundle\Entity\User;
use Insider\UserBundle\Exception\RegistrationException;
use Insider\UserBundle\Exception\EmailExistException;

class InsiderUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();

        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();

        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';

        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());

        $this->userManager->updateUser($user);
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));

        $userChecker = new InsiderUserChecker();

        //when the user is registrating
        if (null === $user) {
            if ($user = $this->userManager->findUserByEmail($response->getEmail())) {
                $ex = new EmailExistException('Пользователь с таким e-mail уже существует');
                $ex->setUser($user);
                throw $ex;
            }

            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //
            $temp_user = $this->userManager->findUserByUsername($response->getNickname());

            $t_username = $response->getNickname() ? $response->getNickname() : $username;
            $t_username = ( $temp_user === null ) ? $t_username : $username;

            $user->setUsername($t_username);
            $user->setEmail($response->getEmail() ? $response->getEmail() : $username );
            $user->setPassword("");
//            $user->setEnabled(false);
//            $user->setLocked(true);

            $this->userManager->updateUser($user);

            $userChecker->checkPreAuth($user);

            return $user;
        }

        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);

        $userChecker->checkPreAuth($user);

        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';

        //update access token
        $user->$setter($response->getAccessToken());

        return $user;
    }
}
