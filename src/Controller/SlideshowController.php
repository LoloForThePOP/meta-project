<?php

namespace App\Controller;

use App\Entity\Slide;
use App\Entity\PPBasic;
use App\Form\SlideshowType;
use App\Form\SlideshowImagesType;
use App\Form\SlideshowColReorderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/slideshow")
 */
class SlideshowController extends AbstractController
{
    /**
     * @Route("/", name="slideshow_index")
     */
    public function index(PPBasic $pp)
    {
        return $this->render('slideshow/index.html.twig', [
            'slug' => $pp->getSlug(),
            'presentation' => $pp,
        ]);
    }

    /**
     * Permet de modifier l'ordre des diapos
     *
     * @Route("/reorder-slides/", name="reorder_slides")
     * 
     */
    public function reorderSlides (PPBasic $presentation, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(SlideshowColReorderType::class, $presentation);
    
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()){
            
            foreach ($presentation->getSlides() as $slide){
                $manager->persist($slide);
            }

            $manager->persist($presentation);
            $manager->flush();

            $this->addFlash(
                'success',
                "Ordre Modifié!"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $presentation->getSlug(),
                'presentation' => $presentation
            ]);
        }

        return $this->render('/slideshow/reorderSlides.html.twig',[
            'slug' => $presentation->getSlug(),
            'form' => $form->createView(),
        ]);

    }

    /**
     * Permet de Supprimer une Diapo
     * 
     * @Route("/delete-slide/{id}", name="delete_slide")
     * 
     * @return Response
     */
    public function delete($slug,  Slide $slide, EntityManagerInterface $manager){

        $manager->remove($slide);
        $manager->flush();

        $this->addFlash(
            'success',
            "La Diapositive a bien été supprimée"
        );

        return $this->redirectToRoute('slideshow_index',[
            'slug' => $slug,
        ]);
    }


    
    /**
     * Permet d'Ajouter des Images
     * 
     * @Route("/add-images",name="slideshow_images_add")
     * 
     * @return Response
     */
    public function addImages (PPBasic $pp, Request $request, EntityManagerInterface $manager){

        $emptyPP = new PPBasic ();

        for($i=0; $i<=5; $i++) {
            ${"image" . $i} = new Slide();
            $emptyPP->addSlide( ${"image" . $i});
        }

        $form = $this->createForm(SlideshowType::class, $emptyPP);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // die(dump( $form->get('slides')->getData()));


            // count previous slide in order to set new slides positions
            $countPreviousSlides = 0;

            if(!$pp->getSlides()->isEmpty()){

                foreach ($pp->getSlides() as $key ) {
                    $countPreviousSlides = $countPreviousSlides + 1 ;
                }
            }

            foreach ($emptyPP->getSlides() as $index => $slide){
                
                if($slide->getSlideFile()!==null){
                    
                    $slide->setMediaType("image");
                    $slide->setPosition($index + $countPreviousSlides);
                    $slide->setPP($pp);
                    $manager->persist($slide);
                }
            }

            $manager->persist($pp);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les Images ont été ajoutées à la fin de la Présentation"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);
        }

        
        return $this->render('slideshow/addImages.html.twig', [
            'slug' => $pp->getSlug(),
            'form' => $form->createView(),
        ]);

    }

}
