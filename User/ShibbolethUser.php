<?php

namespace Queensu\Shibboleth\User;

use Symfony\Component\Security\Core\User\UserInterface;

class ShibbolethUser implements UserInterface
{
    private $roles;
    private $netid;
    private $email;
    private $emplid;
    private $givenName;
    private $surname;
    private $commonName;

    public function __construct($netid, $emplid, $givenName, $surname, $commonName, $email, array $roles = ['ROLE_USER'])
    {
        $this->netid = $netid;
        $this->emplid = $emplid;
        $this->givenName = $givenName;
        $this->surname = $surname;
        $this->commonName = $commonName;
        $this->email = $email;
        $this->roles = $roles;
    }

    public function getNetid()
    {
        return $this->netid;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getEmplid()
    {
        return $this->emplid;
    }

    public function getGivenName()
    {
        return $this->givenName;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getCommonName()
    {
        return $this->commonName;
    }

    public function getUsername()
    {
        return $this->netid;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {

    }

    public function getSalt()
    {

    }

    public function eraseCredentials()
    {

    }

}