<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\UserFollows;
use App\Service\UserFollowService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserFollowsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserFollowsController extends AbstractController
{
    /**
     * 
     * Allow user to follow a project presentation
     * 
     * @Route("/user/new-follow-presentation/{slug}", name="new_follow_presentation")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     */
    public function newFollowPresentation(PPBasic $presentation, UserFollowsRepository $repository, EntityManagerInterface $manager): Response
    {

        $user = $this->getUser();

        $isAlreadyFollowed = $repository->findOneBy([
            'user' => $user->getId(),
            'presentation' => $presentation->getId(),
        ]);

        //dd($isFollowed.length());

        if (!$isAlreadyFollowed) {
            
            $followObject = new UserFollows();

            $followObject->setUser($user)
                        ->setPresentation($presentation);
    
            $manager->persist($followObject);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "Maintenant vous suivez ce projet !"
            );
        }

        else {
            $this->addFlash(
                'success',
                "Vous suivez déjà ce projet !"
            );
        }

        return $this->redirectToRoute('project_show', [

            'slug' => $presentation->getSlug(),

        ]);
    }


    /**
     * 
     * Allow user to access follows notification page
     * 
     * @Route("/user/follows/show-notifications", name="show_follow_notifications")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     */
    public function showFollowNotifications(UserFollowService $userFollowService): Response
    {

        $user = $this->getUser();

        $userFollows = $user->getUserFollows();

        //we get user last time notifications connection
        $lastConnectionDate= $user->getLastNotificationsConnection();

        //we count user notifications
        $countNotifications = $userFollowService->countNotifications($user);

        //we update last time user accessed notification page
        $userFollowService->updateLastConsultationDate($user);

        return $this->render('user_follows/show_notifications.html.twig', [

            'userFollows' => $userFollows,
            'lastConnectionDate' => $lastConnectionDate,
            'currentTime' => $user->getLastNotificationsConnection(),

        ]);

    }



}
