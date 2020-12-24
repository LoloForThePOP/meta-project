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

        return $this->render('footer/about_us.html.twig', [
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
