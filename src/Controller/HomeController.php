<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * Undocumented function
     *
     * @Route("/",name="homepage") 
     * @return Response
     */
    public function home () {
        return $this->render("/home.html.twig");

    }

}


?>
