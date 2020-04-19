<?php

namespace App\Controller;

use App\Entity\Slide;
use App\Entity\PPBasic;
use App\Form\SlideshowType;
use HtmlSanitizer\Sanitizer;
use App\Form\AddVideoSlideType;
use App\Form\SlideshowImagesType;
use App\Form\SlideshowAddTextType;
use App\Form\SlideshowColReorderType;
use HtmlSanitizer\SanitizerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * Permet de modifier l'ordre des diapos en ajax
     *
     * @Route("/ajaxReorderSlides/", name="ajax_reorder_slides")
     * 
    */ 
    public function ajaxReorderSlides(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonSlidesPosition = $request->request->get('jsonSlidesPosition');

            $slidesPosition = json_decode($jsonSlidesPosition,true);

            foreach ($presentation->getSlides() as $slide){

                $newSlidePosition = array_search($slide->getId(), $slidesPosition, false);
                
                $slide->setPosition($newSlidePosition);

                $manager->persist($slide);
            }
            
            $manager->persist($presentation);

            $manager->flush();

            return  new JsonResponse(true);

        }

        return  new JsonResponse();

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
    public function delete($slug, Slide $slide, EntityManagerInterface $manager){

        $manager->remove($slide);
        $manager->flush();

        $this->addFlash(
            'success',
            "La Diapo a bien été supprimée"
        );

        return $this->redirectToRoute('slideshow_index',[
            'slug' => $slug,
        ]);
    }


    
    /**
     * Permet d'Ajouter une Slide de Texte
     * 
     * @Route("/add-text",name="slideshow_text_add")
     * 
     * @return Response
     */
    public function addText (PPBasic $pp, SanitizerInterface $sanitizer, Request $request, EntityManagerInterface $manager){

        $slide = new Slide();

        $form = $this->createForm(SlideshowAddTextType::class, $slide);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // count previous slide in order to set new slides positions
            $counterPreviousSlides = 0;

            if(!$pp->getSlides()->isEmpty()){

                foreach ($pp->getSlides() as $key ) {
                    $counterPreviousSlides ++ ;
                }
            }

            $newSlidePosition = $counterPreviousSlides;
      

            $slideThumbnail = 'Diapo Texte';

            $slide->setMediaType("text");

            $slide->setThumbnail($slideThumbnail);
            $slide->setPosition($newSlidePosition);
            $slide->setPP($pp);
            $manager->persist($slide);

            $manager->persist($pp);
            $manager->flush();

            $this->addFlash(
                'success',
                "La Diapo a été Ajoutée à la Fin du Diaporama"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);
        }
        
        return $this->render('slideshow/addText.html.twig', [
            'presentation' => $pp,
            'slug' => $pp->getSlug(),
            'form' => $form->createView(),
        ]);

    }


   /**
     * Permet d'Editer une Slide de Texte
     * 
     * @Route("/edit-text/{id}",name="slideshow_text_edit")
     * 
     * @return Response
     */
    public function edit($slug, Slide $slide, Request $request, EntityManagerInterface $manager){
        
        $form = $this->createForm(SlideshowAddTextType::class, $slide);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->persist($slide);
            $manager->flush();

            $this->addFlash(
                'success',
                "La Diapo a été Modifiée"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $slug,
            ]);    

        }
        
        return $this->render('slideshow/addText.html.twig', [
            'slug' => $slug,
            'form' => $form->createView(),
        ]);

    }

    
    /**
     * Permet d'Ajouter des Images
     * 
     * @Route("/add-images",name="slideshow_images_add")
     * Security("is_granted('ROLE_ADMIN')")
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
                "Les Images ont été ajoutées à la fin du Diaporama"
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
  
    /**
     * Permet d'Ajouter une Vidéo
     * 
     * @Route("/add-video",name="slideshow_video_add")
     * 
     * @return Response
     */
    public function addVideo (PPBasic $pp, Request $request, EntityManagerInterface $manager) {

        $slide = new Slide ();

        $form = $this->createForm(AddVideoSlideType::class, $slide);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            // count previous slide in order to set new slides positions
            $counterPreviousSlides = 0;

            if(!$pp->getSlides()->isEmpty()){

                foreach ($pp->getSlides() as $key ) {
                    $counterPreviousSlides ++ ;
                }
            }

            $newSlidePosition = $counterPreviousSlides;
      
            $videoUrl = 'https://www.youtube.com/embed/'.$slide->getUrl();

            $videoThumbnail = 'https://img.youtube.com/vi/'.$slide->getUrl().'/mqdefault.jpg';

            $slide->setMediaType("video");

            $slide->setUrl($videoUrl);
            $slide->setThumbnail($videoThumbnail);
            $slide->setPosition($newSlidePosition);
            $slide->setPP($pp);
            $manager->persist($slide);

            $manager->persist($pp);
            $manager->flush();

            $this->addFlash(
                'success',
                "La Vidéo a bien été Ajoutée à la Fin du Diaporama"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);
        }

        return $this->render('slideshow/addVideo.html.twig', [

            'form' => $form->createView(),
            'slug' => $pp->getSlug(), 
            
        ]);

    }

}
