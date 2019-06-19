<?php

namespace Queensu\Shibboleth\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class ShibbolethLogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    public function onLogoutSuccess(Request $request)
    {
        $redirectUrl = ($request->headers->has('shib-identity-provider'))
            ? str_replace('shibboleth', 'profile/Logout', $request->headers->get('shib-identity-provider'))
            : 'http://www.queensu.ca'; //probably shouldn't be here?

        return new RedirectResponse($redirectUrl);
    }

}