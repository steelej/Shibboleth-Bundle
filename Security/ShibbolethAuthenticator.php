<?php

namespace Queensu\Shibboleth\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Shibboleth Authenticator
 *
 * Class ShibAuthenticator
 * @package Queensu\ShibBundle\Security
 */
class ShibbolethAuthenticator extends AbstractGuardAuthenticator
{
    const SHIB_URLS = [
        "https://idptest.queensu.ca/idp/shibboleth",
        "https://login.queensu.ca/idp/shibboleth"
    ];

    private $idpHeader;
    private $usernameHeader;

    public function __construct($shibbolethIDPHeader='shib-identity-provider',$shibbolethUsernameHeader='uid')
    {
        $this->idpHeader=$shibbolethIDPHeader;
        $this->usernameHeader=$shibbolethUsernameHeader;
    }

    /**
     * In theory, this method should never end up being called.......
     *
     * This only gets called if getCredentials, checkCredentials, or getUser
     * returns null or throws an exception.
     *
     * Also, if shib headers are non existent or malformed then the 'supports'
     * method will return false. If this happens AND this is the only authenticator
     * configured then 'start' will be called.
     *
     * tl;dr: if shib and the userProvider are configured properly
     * then this wont be called.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        //possible to do: redirect to shib login?
        return new Response('Authentication required', 401);
    }

    /**
     * Only use this authenticator if shib headers exist
     *
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return $request->headers->has($this->idpHeader);
    }

    /**
     * We don't handle user passwords so in our case
     * 'credentials' will be a netid and shib url.
     *
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request)
    {
        if(!$request->headers->has($this->idpHeader) || !$request->headers->has($this->usernameHeader)){
            throw new \UnexpectedValueException('Invalid Shibboleth header.');
        }

        return [
            'shib-identity-provider' => $request->headers->get($this->idpHeader),
            'uid' => $request->headers->get($this->usernameHeader)
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['uid']);
    }

    /**
     * In the case of shib, we don't need to check a password
     * but we will double check that the shib url is in our list
     *
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return in_array($credentials['shib-identity-provider'], self::SHIB_URLS);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

    }

    public function supportsRememberMe()
    {
        return false;
    }

}