<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
