<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\PPBasic;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class AccessPresentationVoter extends Voter
{

    const VIEW = 'view';
    const EDIT = 'edit';


    protected function supports($attribute, $subject)
    {
       
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on 'Project Presentation' objects
        if (!$subject instanceof PPBasic) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $presentation = $subject;

        switch ($attribute) {

            case self::VIEW:
                return $this->canView($presentation, $token);

            case self::EDIT:
                return $this->canEdit($presentation, $token);

        }

        throw new \LogicException('This code should not be reached !');
    }

    private function canView(PPBasic $presentation, TokenInterface $token)
    {

        // !! it is quirck, but $token->getUser() represents an user ONLY if user is logged in. Otherwise, it is not an instance of class User. To check if user is not logged in (i.e. anonymous), test !$token->getUser() instanceof UserInterface

        $user = $token->getUser();

        // if user can edit, he can view
        if ($this->canEdit($presentation, $token)) {
            return true;
        }

        // if presentation is not published, other users can not view it
        if (! $presentation->getIsPublished()) {
            return false;
        }

        return true;
        
    }

    private function canEdit(PPBasic $presentation, TokenInterface $token)
    {     

        $user = $token->getUser();

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
