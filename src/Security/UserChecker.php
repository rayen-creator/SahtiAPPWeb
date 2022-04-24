<?php

namespace App\Security;

use App\Entity\Client;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if ($user->getUsername()=="Admin@sahti.com"){
            $test="admin";
        }
        else if ($user->getIsblocked()) {
            throw new CustomUserMessageAuthenticationException("You're banned Contact us for more infos.");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {

    }
}