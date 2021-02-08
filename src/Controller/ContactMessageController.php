<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\ContactMessage;
use App\Form\ContactMessageType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactMessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ContactMessageController extends AbstractController
{
    /**
     * @Route("/projects/{slug}/messages/", name="index_project_messages", methods={"GET"})
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator() ")
     */
    public function indexByProject(PPBasic $presentation,ContactMessageRepository $contactMessageRepository): Response
    {
        
        $projectMessages = $contactMessageRepository->findBy
            (
                [
                    'presentation' => $presentation->getId(),
                ],
                [
                    'createdAt' => 'DESC',
                ],
                
            );

        return $this->render('contact_message/index.html.twig', [
                'contact_messages' => $projectMessages,
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation,
        ]);
    }

      /**
     * @Route("/user/messages/show", name="show_user_messages")
     * 
     * @Security("is_granted('ROLE_USER')")
     */
/*     public function indexByUser()
    {
        $messages = $this->getUser()->getMessages();

        return $this->render('contact_message/user_list.html.twig', [
            'contact_messages' => $messages,
        ]);
    } */


    
    /** 
     * Allow to Display a Private Message (example : in a Modal Box).
     * 
     * @Route("/projects/{slug}/messages/ajaxShowMessage", name="ajax_get_message_content") 
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator() ")
     * 
    */ 
    public function ajaxMessageContent(Request $request, ContactMessageRepository $contactMessageRepository, EntityManagerInterface $manager, PPBasic $presentation) {

        if ($request->isXmlHttpRequest()) {

            $messageId = $request->request->get('messageId');

            $contactMessage = $contactMessageRepository->findOneById($messageId);

            $messageContent = $contactMessage->getContent();

            // we set the message has been consulted

            $contactMessage->setHasBeenConsulted(true);
            $manager->persist($contactMessage);
            $manager->flush();

            $dataResponse = [
                'messageContent' => $messageContent,
            ];

            return new JsonResponse($dataResponse);
            
        }

    }

    

    /**
     * Allow to Delete a Private Message
     * 
     * @Route("/projects/{slug}/messages/ajaxDeleteMessage/", name="ajax_delete_message")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator() ")
     * 
     * @return Response
     */
   public function deleteMessage(Request $request, PPBasic $presentation, EntityManagerInterface $manager, ContactMessageRepository $contactMessageRepository){

        if ($request->isXmlHttpRequest()) {

            $messageId = $request->request->get('messageId');

            $message = $contactMessageRepository->findOneById($messageId);

            $feedbackCode = false;

            if ($presentation->getContactMessages()->contains($message)) {

                $presentation->removeContactMessage($message);
                
                $manager->remove($message);

                $manager->persist($presentation);
                $manager->flush();

                $feedbackCode = true;
            }

            $dataResponse = [
                'feedbackCode' => $feedbackCode,
            ];

            return new JsonResponse($dataResponse);

        }
    }

    
    /**
     * Allow to create and send a private message
     * @Route("/projects/{slug}/messages/new/", name="contact_message_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function new (PPBasic $pp, Request $request, \Swift_Mailer $mailer): Response
    {
        $contactMessage = new ContactMessage();
        $form = $this->createForm(ContactMessageType::class, $contactMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactMessage ->setPresentation($pp)
                            ->addReceiver($pp->getCreator());            ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactMessage);
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                "Votre Message a été envoyé"
            );

            //die( dump($pp->getCreator()->getEmail()));

            
            // we create an e-mail for project creator
            $message = (new \Swift_Message('Nouveau Message Reçu'))
                ->setFrom(['noreply@projetdesprojets.fr'=>'Projet des Projets'])
                ->setTo($pp->getCreator()->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/newPrivateMessage.html.twig',[

                            'presentation' => $pp,
                        ]
                    ),
                    'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            return $this->redirectToRoute('project_show', [
                'slug' => $pp->getSlug(),
            ]);
        }

        return $this->render('contact_message/new.html.twig', [
            'contact_message' => $contactMessage,
            'slug' => $pp->getSlug(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * allow to get embed html message display
     * 
     * @Route("/messages/ajax-show-embed", name="show_embed_message")
     * 
     */
    public function showEmbed(Request $request, ContactMessageRepository $repository, EntityManagerInterface $manager)
    {
        
        if ($request->isXmlHttpRequest()) {

            //get selected news

            $idMessage = $request->request->get('idEntity');
            
            $message = $repository->findOneById($idMessage);

            // for the moment, only presentation creators can read messages

            if ($message->getPresentation()->getCreator() !== $this->getUser()) {
                return false;
            }
            
            $message->setHasBeenConsulted(true);

            $manager->persist($message);
            $manager->flush();

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'contact_message/show_embed.html.twig', 

                    [
                        'message' => $message,
                    ]
                ),
            ];

            //dump($dataResponse);

            return new JsonResponse($dataResponse);

        }

    }


  
}
