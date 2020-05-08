<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
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
    public function home (EntityManagerInterface $manager) {

        // we show the last 20 inserted projects

        $lastInsertedProjects = $manager->createQuery('SELECT p.title, p.keywords, p.thumbnailName, p.goal, p.slug, p.title FROM App\Entity\PPBasic p WHERE p.isPublished=true ORDER BY p.createdAt DESC')->setMaxResults('10')->getResult();

        return $this->render("/home.html.twig", [
            'presentations' => $lastInsertedProjects,
        ]);

    }

}


?>
