<?php
namespace HackThursday\Entity;

class FacebookUser
{
    private $username;
    private $password;

    public function __construct($username = null, $password = null)
    {
        if ($username) {
            $this->setUsername($username);
        }

        if ($password) {
            $this->setPassword($password);
        }
    }

    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }
}