<?php

namespace App\Security;

use App\Security\User as AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {

        if (!$user->getIsAllowed()) {

           /*  dump($user->getIsAllowed());
            die();
            */
            throw new AccessDeniedException(
                'Inactive account cannot log-in.'
            );
        }

        

        if (!$user instanceof AppUser) {
            return;
        }
        


        // user is deleted, show a generic Account Not Found message.
       /*  if ($user->isDeleted()) {
            throw new AccountDeletedException();
        } */
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        /* if ($user->getIsAllowed()==false) {
            throw new CustomUserMessageAuthenticationException(
                'Inactive account cannot log-in.'
            );
        } */

        // user account is expired, the user may be notified
        /* if ($user->isExpired()) {
            throw new AccountExpiredException('...');
        } */
    }
}

?>