<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RightsController extends AbstractController
{
    /**
     * @Route("/rights", name="rights")
     */
    public function index(): Response
    {
        return $this->render('rights/index.html.twig', [
            'controller_name' => 'RightsController',
        ]);
    }
}
