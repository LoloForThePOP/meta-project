<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/login", name="account_login")
     * 
     * @return Response
     * 
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
        ]);
    }

    
    /**
     * Permet de se Déconnecter
     * @Route("/logout",name="account_logout")
     * @return void
     */
    public function logout(){

    }

    /**
     * Permet d'afficher le formulaire d'inscription
     * @Route("/register",name="account_register")
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        
        $user = new User();

        $form=$this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre Compte a bien été Créé! Vous pouvez maintenant vous connecter.'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig',[
            'form' => $form->createView(),
        ]);

    }

    /**
     * Permet d'Editer le Profil d'un Utilisateur
     * @Route("account/profile",name="account_profile")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager){

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les Données ont étées enregistrées avec succès!'
            );
        }

        return $this->render('/account/profile.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Permet à l'Utilisateur de Modifier son Mot de Passe
     * 
     * @Route("/account/update-password",name="account_update_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){
        
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();
        
        $form=$this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            //Vérifier que l'utilisateur renseigne bien son mot de passe actuel (avant de pouvoir le modifier)
            
            if (!$encoder->isPasswordValid($user, $passwordUpdate->getOldPassword())){
                $form->get('oldPassword')->addError(new FormError("Il faut écrire ici votre mot de passe actuel"));

            }else{

                $newPassword=$passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre Mot de Passe a été Modifié avec Succès'
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('/account/password.html.twig',[
            'form' => $form->createView(),
            
        ]);

    }

      /**     
     * Permet de retourner la Page de l'utilisateur
     * @Route("/account",name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount (){
        
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
