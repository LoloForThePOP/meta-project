<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Persorg;
use App\Form\AccountType;
use App\Form\PersorgType;
use App\Form\ResetPassType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\PresentationsRightsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AccountController extends AbstractController
{
    /**
     * Display login form
     * 
     * here we check if user is disabled (= not allowed) by admins, thanks to UserChecker (a security service located in App/Security). The UserChecker is also activated via security.yaml.
     * 
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
     * Allow to Disconnect
     * 
     * @Route("/logout",name="account_logout")
     * @return void
     */
    public function logout(){
        
    }
    

    /**     
     * Allow to show user profile
     * @Route("/account",name="account_index")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function myAccount (PresentationsRightsService $PresentationsRightsService){

        $user = $this->getUser();

        $userCreations = $user->getPresentations();

        $userContributions = $PresentationsRightsService->getUserPresentationsByRightType ($user, 'edit');
        
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'userCreations' => $userCreations,
            'userContributions' => $userContributions,
        ]);

    }
    

    /**
     * Subscribe to the Website (Display Register Form)
     * 
     * @Route("/register", name="account_register")
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer) {
        
        $user = new User();

        $form=$this->createForm(
            
            RegistrationType::class, 
            
            $user, 
            
            array(

                // Time protection
                'antispam_time'     => true,
                'antispam_time_min' => 8,
                'antispam_time_max' => 3600,
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();
            
            $message = (new \Swift_Message('Merci pour votre inscription sur le site du Projet des Projets'))
            ->setFrom(['contact@projetdesprojets.com'=>'Projet des Projets'])
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'emails/registred.html.twig'
                ),
                'text/html'
            );
            
            $mailer->send($message);

            $this->addFlash(
                'success',
                'Votre compte vient d\'être créé ! Vous pouvez maintenant vous connecter.'
            );
            
            return $this->redirectToRoute('edit_public_profile',[
                'persorg_id' => $user->getPersorg()->getId(),
                'context' => 'registration',
            ]);
        }

        return $this->render('account/registration.html.twig',[
            'form' => $form->createView(),
        ]);

    }

    /**
     * Allow user to access update account menu
     * 
     * @Route("account/update-menu",name="account_update_menu")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function accessMenu(Request $request, EntityManagerInterface $manager){

        return $this->render('/account/update_menu.html.twig',[
        ]);
    }


    /**
     * Allow user to modify his account email
     * 
     * @Route("account/update-email",name="account_update_email")
     * 
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return Response
     */
    public function updateEmail(Request $request, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Adresse e-mail modifiée.'
            );

            return $this->redirectToRoute('user_show',[
                'id' => $user->getId(),
            ]);
        }

        return $this->render('/account/change_email.html.twig',[
            'form' => $form->createView(),
        ]);
    }


    
    /**
     * Allow User to Modify his Password
     * 
     * @Route("/account/update-password",name="account_update_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager){

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
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
     * 
     * forgotten password : user request an email with reset page link
     * 
     * @Route("/forgotten-password-request", name="forgotten_password_request")
     * 
     */
    public function forgottenPass(Request $request, UserRepository $userRepo, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
    ): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $donnees = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
            $user = $userRepo->findOneByEmail($donnees['email']);

            // Si l'utilisateur n'existe pas
            if ($user === null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
                
                // On retourne sur la page de connexion
                return $this->redirectToRoute('account_login');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try{
                $user->setResetPassToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('account_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('forgotten_password_create', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            // On génère l'e-mail
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom(['noreply@projetdesprojets.fr'=>'Réinitialisation Mot de Passe - Projet des Projets'])
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site projetdesprojets.fr. Veuillez cliquer sur le lien suivant : " . $url,
                    'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash de confirmation
            $this->addFlash('success', 'Un e-mail de réinitialisation de votre mot de passe vous a été envoyé. Ouvrez le. à bientôt.');

            // On redirige vers la page de login
            return $this->redirectToRoute('homepage');
        }

        // On envoie le formulaire à la vue
        return $this->render(
            'account/forgotten_password_request.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * forgotten password : user create a new password
     * 
     * @Route("/forgotten-password-create-new/{token}", name="forgotten_password_create")
     * 
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donné
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['resetPassToken' => $token]);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Une erreur est survenue : Token Inconnu');
            return $this->redirectToRoute('account_login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            $user->setResetPassToken(null);

            // On chiffre le mot de passe
            $user->setHash($passwordEncoder->encodePassword($user, $request->request->get('password')));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('success', 'Votre nouveau mot de passe est enregistré. Vous pouvez désormais l\'utiliser.');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('account_login');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('account/forgotten_password_create.html.twig', ['token' => $token]);
        }

    }



}
