<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Form\WebsiteCollectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/websites")
 */
class WebsiteController extends AbstractController
{
    /**
     * @Route("/", name="websites_index")
     */
    public function index (PPBasic $presentation, Request $request, EntityManagerInterface $manager)
    {

        $form = $this->createForm(WebsiteCollectionType::class, $presentation);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            foreach ($presentation->getWebsites() as $website){

                $website->setPresentation($presentation);
                $manager->persist($website);

            }
 
            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les Modifications ont étées effectuées!"
            );
        }
    
        return $this->render('websites/index.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'presentation' => $presentation,
        ]);
    }

}
