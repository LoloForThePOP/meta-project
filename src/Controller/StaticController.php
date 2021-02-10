<?php

namespace App\Controller;

use App\Entity\ContactWebsite;
use App\Form\ContactWebsiteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticController extends AbstractController
{
    /**
     * @Route("/quick-user-guide", name="quick_user_guide")
     */
    public function index()
    {
        return $this->render('static/quick_user_guide.html.twig', [
            'controller_name' => 'StaticController',
        ]);
    }

     /**
     * @Route("/contact-us", name="contact_website")
     */
    public function contactWebsite(Request $request, \Swift_Mailer $mailer)
    {

        $contactMessage = new ContactWebsite();

        $form = $this->createForm(ContactWebsiteType::class, $contactMessage, array(
            // Time protection
            'antispam_time'     => true,
            'antispam_time_min' => 10,
            'antispam_time_max' => 3600,
        
            // Honeypot protection
            'antispam_honeypot'       => true,
            'antispam_honeypot_class' => 'onpt',
            'antispam_honeypot_field' => 'email-repeat',
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactMessage);
            $entityManager->flush();

            //sending a message to website administration

            $message = (new \Swift_Message('Un utilisateur a envoyé un message'))
            ->setFrom(['noreply@projetdesprojets.com'=>'Projet des Projets'])
            ->setTo('contact@projetdesprojets.com')
            ->setBody(
                $this->renderView(
                    'emails/registred.html.twig'
                ),
                'text/html'
            );
            
            $mailer->send($message);

            $this->addFlash(
                'success',
                'Merci pour l\'envoi de votre message. Nous souhaitons le consulter dans les plus brefs délais'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('static/contact_website.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }


}