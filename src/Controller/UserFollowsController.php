<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\UserFollows;
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
                "Vous suivez ce projet !"
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
    public function showFollowNotifications(EntityManagerInterface $manager): Response
    {

        $user = $this->getUser();

        $userFollows = $user->getUserFollows();

        //we get user last time notifications connection

        $lastConnection= $user->getLastNotificationsConnection();

        //count user notifications :

        //foreach ($userFollows as $followRow) {
        //    $followRow->getPresentation()->getMajorLogs()->getLastUpdate();
        //}





        return $this->render('user_follows/show_notifications.html.twig', [

            'userFollows' => $userFollows,
            'lastConnection' => $lastConnection,

        ]);

    }



}
