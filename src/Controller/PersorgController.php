<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PersorgController extends AbstractController
{
    /**
     * @Route("/persorg", name="persorg")
     */
    public function index()
    {
        return $this->render('persorg/index.html.twig', [
            'controller_name' => 'PersorgController',
        ]);
    }
}
