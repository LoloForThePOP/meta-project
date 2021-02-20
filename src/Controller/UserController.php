<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Persorg;
use App\Form\PersorgType;
use App\Form\ReplyCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
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
     * @Route("account/public-profile/{context?}", name="edit_public_profile")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function publicProfile($context, Request $request, EntityManagerInterface $manager){

        $userPersorg = $this->getUser()->getPersorg();

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
    public function newCommentsList(CacheInterface $cache, EntityManagerInterface $manager, Request $request){

        $user = $this->getUser();

        //we cache last time user went to new comments page for 20 min, so that he can go back to new comments page within 20 minutes, and still see new comments highlighted.

        $lastTimeConnection = $cache->get('last-time-connection', function (ItemInterface $item) {

            $item->expiresAfter(1200);

            return $this->getUser()->getLastNewCommentsConnection();

        });
        
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

        //reply to a comment form

           $replyComment = new Comment();

           $replyForm = $this->createForm(ReplyCommentType::class, $replyComment, array(
               // Time protection
               'antispam_time'     => true,
               'antispam_time_min' => 10,
               'antispam_time_max' => 3600,
           ));
   
           $replyForm->handleRequest($request);
   
           if ($replyForm->isSubmitted() && $replyForm->isValid()) {
   
               //we retrieve parent comment
   
               $parentCommentId = $replyForm->get('parentCommentId')->getData();
   
               $entityManager = $this->getDoctrine()->getManager();
               $parentComment = $entityManager->getRepository(Comment::class)->findOneById($parentCommentId);
   
               $replyComment->setParent($parentComment);
   
               $replyComment->setPresentation($parentComment->getPresentation())
                       ->setUser($user);
   
               $entityManager = $this->getDoctrine()->getManager();
               $entityManager->persist($replyComment);
               $entityManager->flush();
   
               $this->addFlash(
                   'success',
                   'Votre commentaire est ajouté.'
               );
   
               return $this->redirectToRoute('show_user_new_comments', [
                   ]);
           }

        //we update last time user accessed to this page (consult new comments)
        $user->setLastNewCommentsConnection(new \DateTime('now'));

        $manager->persist($user);
        $manager->flush();

        return $this->render('/user/list_new_comments.html.twig',[
     
            'lastTimeConnection' => $lastTimeConnection,
            'replyForm' => $replyForm->createView(),
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
