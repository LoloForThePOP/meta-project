<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\PPBasic;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AccessPresentationVoter extends Voter
{

    /* const VIEW = 'view'; */

    const EDIT = 'edit';


    protected function supports($attribute, $subject)
    {
       
        if (!in_array($attribute, [/* self::VIEW,  */self::EDIT])) {
            return false;
        }

        // only vote on `Post` objects
        if (!$subject instanceof PPBasic) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        $presentation = $subject;

        // ... (check conditions and return true to grant permission) ...

        switch ($attribute) {

            /* case self::VIEW:
                return $this->canView($presentation, $user); */

            case self::EDIT:
                return $this->canEdit($presentation, $user);

        }

        throw new \LogicException('This code should not be reached !');
    }

    /* private function canView(PPBasic $presentation, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($presentation, $user)) {
            return true;
        }

        // we check if project presentation is private (not implemented yet)
        return !$presentation->isPrivate();
    } */

    private function canEdit(PPBasic $presentation, User $user)
    {     

        // if user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        // otherwise we check if user is presentation's creator
        if ($user==$presentation->getCreator()) {
            return true;
        }

        //otherwise we check if users is included in presentation rights, along with Edit presentation capability
        foreach ($presentation->getRights() as $right) {
            
            if ($right->getUser() == $user && $right->getType()=='edit') {
                
                return true;
            }

            return false;
        }
        
        return false;
    }





}
