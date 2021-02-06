<?php

namespace App\Service;

use App\Entity\User;

class PresentationsRightsService {

    /**
    * Returns presentations that user can view or edit or any other kinds of rights.
    */
    public function getUserPresentationsByRightType (User $user, string $rightType){

        $userPresentationsAccess = $user->getRights();

        $projectList = [];

        foreach ($userPresentationsAccess as $right) {
            
            if ($right->getType() == $rightType) {

                $projectList [] = $right->getPresentation();
            }

        }

        return $projectList;

    }

}
