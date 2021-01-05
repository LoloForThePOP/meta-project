<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserFollowService {

    protected $manager;
    protected $session;

    public function __construct(EntityManagerInterface $manager, SessionInterface $session) {

        $this->manager = $manager;

        $this->session = $session;

    }


    /**
     * 
     * Allow to count user notifications
     * 
     */
    public function countNotifications(User $user) : int {

        $countNotifications = 0;

        $userFollows = $user->getUserFollows();

        //we get user last time notifications page access
        $lastConnectionDate= $user->getLastNotificationsConnection();

        foreach ($userFollows as $followRow) {

            if ($followRow->getPresentation()->getMajorLogs() !== null) {

                $ppLastMajorUpdateDate = $followRow->getPresentation()->getMajorLogs()->getUpdatedAt();

                if ($lastConnectionDate < $ppLastMajorUpdateDate) {
    
                    $countNotifications++;            
                
                }
            }

        }

        $countNotifications;

        return $countNotifications;

    }

    /**
     * Allow to update last time user connect to notification page
     *
     * @return void
     */
    public function updateLastConsultationDate(User $user){

        //lastConnectionDateUpdate
        $user->setLastNotificationsConnection(new \DateTime('now'));

        $manager=$this->manager;

        $manager->persist($user);
        $manager->flush();

    }


}
