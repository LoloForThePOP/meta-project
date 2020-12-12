<?php

namespace App\Controller;

use App\Entity\ContactWebsite;
use App\Form\ContactWebsiteType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FooterController extends AbstractController
{
    /**
     * @Route("/credits", name="credits_show")
     */
    public function creditsShow()
    {
        return $this->render('footer/credits.html.twig', [
            
        ]);
    }

    /**
     * @Route("/about-us", name="about_us")
     */
    public function aboutUsShow(Request $request, \Swift_Mailer $mailer)
    {

        $contactMessage = new ContactWebsite();

        $form = $this->createForm(ContactWebsiteType::class, $contactMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactMessage);
            $entityManager->flush();

            $messageContent=$contactMessage->getContent();

            // We send an email to the website contact (type your own)
            $message = (new \Swift_Message('Envoi Message de Contact'))
            ->setFrom(['contact@projetdesprojets.com'=>'Message de Contact'])
            ->setTo('contact@projetdesprojets.com')
            ->setBody(
                "Message Title : test envoi message <br><br>
                Message Content".$messageContent,
                'text/html'
            );

            $mailer->send($message);

            $this->addFlash(
                'success',
                'Merci pour l\'envoi de votre message. Nous souhaitons le traiter dans les plus brefs délais'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('footer/about_us.html.twig', [

            'form' => $form->createView(),
            
        ]);
    }

   

    /**
     * @Route("/terms", name="terms_show")
     */
    public function termsShow()
    {
        return $this->render('footer/terms.html.twig', [
            
        ]);
    }




}
