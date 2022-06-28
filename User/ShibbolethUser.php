<?php

namespace Queensu\Shibboleth\User;

use Symfony\Component\ErrorHandler\Error\UndefinedMethodError;
use Symfony\Component\Security\Core\User\UserInterface;

class ShibbolethUser implements UserInterface
{
    private $username;
    private $roles;
    private $extraFields;

    public function __construct($username, array $extraFields=[], array $roles = ['ROLE_USER'])
    {
        $this->username = $username;
        $this->extraFields=$extraFields;
        $this->roles = $roles;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
    
    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword()
    {
        return null;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        //nothing to do
    }

    /**
     * Use the magic __call function to handle get<Field> methods for the extra attributes.
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (strpos($name,"get") === 0) {
            $field = lcfirst(substr($name,3));
            if(array_key_exists($field,$this->extraFields)) {
                return $this->extraFields[$field];
            }
        }
        throw new UndefinedMethodError("Attempted to call an undefined method named \"$name\" of class \"" . self::class . "\"",new \ErrorException());
    }

    /**
     * Returns an array of the extra headers to parse where the key is the field name
     * @return array
     */
    public static function extraHeaders() {
       return [];
    }
}
