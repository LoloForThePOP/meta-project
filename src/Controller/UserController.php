<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Persorg;
use App\Form\PersorgType;
use App\Repository\PersorgRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /*

        Access to Notification Page : in UserFollowsController.

    */

    /**
     * @Route("/user/{id}", name="user_show")
     */
    public function index(User $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

  
    
    /**
     * Allow user to manage its public profile
     * 
     * @Route("account/public-profile/{persorg_id}/{context?}", name="edit_public_profile")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function publicProfile($context, $persorg_id, PersorgRepository $persorgRepository, Request $request, EntityManagerInterface $manager){

        $userPersorg = $persorgRepository->findOneById($persorg_id);

        $persorgForm = $this->createForm(PersorgType::class, $userPersorg); 

        $persorgForm->handleRequest($request);

        if($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $manager->persist($userPersorg);

            $manager->flush();

            $this->addFlash(
                'success',
                'Les Modifications ont étées enregistrées.'
            );

            return $this->redirectToRoute('user_show',[
                'id' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('/user/edit_public_profile.html.twig',[
     
            'persorgForm' => $persorgForm->createView(),
            'userPersorg' => $userPersorg,
            'context' => $context,
        ]);

    }
    
    /**
     * Allow user to see comments he has created
     * 
     * @Route("user/comments/list",name="list_user_comments")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function commentsIndex(){

        return $this->render('/user/show_comments.html.twig',[

        ]);

    }

    
    /**
     * Allow user to access new comments received from projects he presents
     * 
     * @Route("user/comments/new/show", name="show_user_new_comments")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function newCommentsList(EntityManagerInterface $manager){

        $user = $this->getUser();

        $lastTimeConnection = $user->getLastNewCommentsConnection();

        $newComments = $user->getCommentsSinceDate($lastTimeConnection);

        //to get some context, we get its parent

        $newCommentsParents=[];

        foreach ($newComments as $newComment) {
            
            if ($newComment->getParent() !== null) {

                //we make sure comment's parent is not already saved
                $candidate = $newComment->getParent();

                if (! in_array($candidate, $newCommentsParents)) {
                    $newCommentsParents[] = $candidate;
                }
            }
            else {
                $newCommentsParents[] = $newComment;
            }
        }

        //we update last time user accessed new comments page
        //$user->setLastNewCommentsConnection(new \DateTime('now'));

        $manager->persist($user);
        $manager->flush();

        return $this->render('/user/list_new_comments.html.twig',[
     
            'lastTimeConnection' => $lastTimeConnection,
            'newComments' =>  $newCommentsParents,

        ]);

    }



    /**
     * Allow user to access its messages list
     * 
     * @Route("user/messages/show", name="show_user_messages")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function messagesList(){

        return $this->render('/user/list_unread_messages.html.twig',[
     
            'unreadMessages' => $this->getUser()->getUnreadMessages(),

        ]);

    }



}
