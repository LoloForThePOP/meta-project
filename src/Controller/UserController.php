<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Persorg;
use App\Form\PersorgType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

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
     * @Route("account/public-profile/{id}",name="edit_public_profile")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function publicProfile(Persorg $userPersorg, Request $request, EntityManagerInterface $manager){

        $persorgForm = $this->createForm(PersorgType::class, $userPersorg); 

        $persorgForm->handleRequest($request);

        if($persorgForm->isSubmitted() && $persorgForm->isValid()){

            $manager->persist($userPersorg);

            $manager->flush();

            $this->addFlash(
                'success',
                'Les Modifications ont étées enregistrées avec succès!'
            );

            return $this->redirectToRoute('user_show',[
                'id' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('/user/edit_public_profile.html.twig',[
     
            'persorgForm' => $persorgForm->createView(),
            'userPersorg' => $userPersorg,
        ]);

    }
    
    /**
     * Allow user to see its comments list
     * 
     * @Route("user/{id}/comments/list",name="list_user_comments")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function commentsIndex(User $user){

        return $this->render('/user/show_comments.html.twig',[
     
            'user' => $user,
        ]);

    }

}
