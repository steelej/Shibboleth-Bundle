<?php

namespace Queensu\Shibboleth\User;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ShibbolethUserProvider implements UserProviderInterface
{
    private $request;
    private $usernameHeader;
    private $userClass;

    public function __construct(RequestStack $stack, $userClass = ShibbolethUser::class, $usernameHeader='uid')
    {
        $this->request = $stack->getCurrentRequest();
        if(!class_exists($userClass) || ($userClass !== ShibbolethUser::class && !array_key_exists(ShibbolethUser::class,class_parents($userClass))))
            throw new \InvalidArgumentException("The class $userClass must exist, and must be or extend " . ShibbolethUser::class);
        $this->userClass=$userClass;
        $this->usernameHeader = $usernameHeader;
    }

    public function loadUserByUsername($username)
    {
        if(!$username === $this->request->headers->get($this->usernameHeader)) {
            throw new UsernameNotFoundException('Username not found');
        }
        $headers = $this->userClass::extraHeaders();
        $extraFields = array_combine(
            array_keys($headers),
            array_map(function($header) { return $this->request->headers->get($header); },$headers)
        );
        return new $this->userClass(
            $this->request->headers->get($this->usernameHeader),
            $extraFields
        );
    }
    public function loadUserByIdentifier($identifer): UserInterface
    {
        return $this->loadByUsename($identifier);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === $this->userClass;
    }

}
