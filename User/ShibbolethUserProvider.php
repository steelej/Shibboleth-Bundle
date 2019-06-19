<?php

namespace Queensu\Shibboleth\User;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ShibbolethUserProvider implements UserProviderInterface
{
    private $request;

    public function __construct(RequestStack $stack)
    {
        $this->request = $stack->getCurrentRequest();
    }

    public function loadUserByUsername($username)
    {
        if(!$username === $this->request->headers->get('uid')) {
            throw new UsernameNotFoundException('Username not found');
        }

        return new ShibbolethUser(
            $this->request->headers->get('uid'),
            $this->request->headers->get('queensucaemplid'),
            $this->request->headers->get('givenname'),
            $this->request->headers->get('surname'),
            $this->request->headers->get('common-name'),
            $this->request->headers->get('email'),
            ['ROLE_USER']
        );
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === ShibbolethUser::class;
    }

}
