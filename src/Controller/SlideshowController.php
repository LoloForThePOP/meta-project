<?php

namespace App\Controller;

use App\Entity\Slide;
use App\Entity\PPBasic;
use App\Form\SlideshowType;
use HtmlSanitizer\Sanitizer;
use App\Form\AddVideoSlideType;
use App\Form\SlideshowImagesType;
use App\Form\SlideshowAddTextType;
use App\Repository\SlideRepository;
use App\Form\SlideshowColReorderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * 
 */
class SlideshowController extends AbstractController
{
    /**
     * @Route("/projects/{slug}/slideshow/", name="slideshow_index")
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
     * @Route("/projects/{slug}/slideshow/ajax-reorder-slides/", name="ajax_reorder_slides")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
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
     * Ajax slide deletion
     * 
     * @Route("/projects/{slug}/slideshow/ajax-delete-slide/", name="ajax_delete_slide")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     */
    public function ajaxDeleteSlide(PPBasic $presentation, Request $request, SlideRepository $slideRepository, EntityManagerInterface $manager){

        if ($request->isXmlHttpRequest()) {

            $idSlide = $request->request->get('idSlide');

            $slide = $slideRepository->findOneById($idSlide);

            if ($presentation->getSlides()->contains($slide)) {

                $presentation->removeSlide($slide);
                
                $manager->remove($slide);

                $manager->persist($presentation);

                $manager->flush();
            }

            $dataResponse = [
            ];

            return new JsonResponse($dataResponse);

        }

    }


    
    /**
     * Allow to Integrate a Rich Text Slide into a Diapo-Pitch
     * 
     * @Route("/projects/{slug}/slideshow/add-text",name="slideshow_text_add")
     * 
     * @Security("is_granted('ROLE_USER') and user === pp.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function addText (PPBasic $pp, Request $request, EntityManagerInterface $manager){

        $slide = new Slide();

        $form = $this->createForm(SlideshowAddTextType::class, $slide);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            if ($slide->getPosition() == NULL) {
                    
                // count previous slide in order to set new slides positions
                $counterPreviousSlides = 0;

                if(!$pp->getSlides()->isEmpty()){

                    foreach ($pp->getSlides() as $key ) {
                        $counterPreviousSlides ++ ;
                    }
                }

                $newSlidePosition = $counterPreviousSlides;
      
            }

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
     * Allow to Modify a Rich Text Slide into a Diapo-Pitch
     * 
     * @Route("/projects/{slug}/slideshow/edit-text/{slide_id}",name="slideshow_text_edit")
     * 
     * @Entity("slide", expr="repository.find(slide_id)")
     * 
     * @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function edit(PPBasic $presentation, Slide $slide, Request $request, EntityManagerInterface $manager){
        
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
                'slug' => $presentation->getSlug(),
            ]);    

        }
        
        return $this->render('slideshow/addText.html.twig', [
            'slug' => $presentation->getSlug(),
            'form' => $form->createView(),
        ]);

    }

    
    /**
     * Allow to create an image slide
     *  
     * @Route("/projects/{slug}/slideshow/add-image/", name="slideshow_images_add")
     * 
     * @Security("is_granted('ROLE_USER') and user === pp.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function addImageSlide (PPBasic $pp, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(SlideshowImagesType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $slide = $form->getData();

            // count previous slide in order to set new slide position
            $countPreviousSlides = 0;

            if(!$pp->getSlides()->isEmpty()){

                foreach ($pp->getSlides() as $key ) {
                    $countPreviousSlides = $countPreviousSlides + 1 ;
                }
            }
            
            $slide->setMediaType("image");
            $slide->setPosition($countPreviousSlides+1);
            $slide->setPP($pp);
            
            $manager->persist($slide);
            $manager->persist($pp);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'image a été ajoutée à la fin du Diaporama"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);

        }

        return $this->render('slideshow/addImage.html.twig', [
            'slug' => $pp->getSlug(),
            'form' => $form->createView(),
        ]);

    }
    
    /**
     * Allow to update an image Slide
     *  
     * @Route("/projects/{slug}/slideshow/edit-image/{id_slide}", name="slideshow_image_edit")
     * 
     *  @Entity("slide", expr="repository.find(id_slide)")
     * 
     * @Security("is_granted('ROLE_USER') and user === pp.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function editImageSlide (PPBasic $pp, Request $request, EntityManagerInterface $manager, Slide $slide){

        $form = $this->createForm(SlideshowImagesType::class, $slide);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
                             
            $manager->persist($slide);
            $manager->flush();

            $this->addFlash(
                'success',
                "La diapo texte a été mise à jour"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);

        }

        return $this->render('slideshow/editImage.html.twig', [
            'slug' => $pp->getSlug(),
            'form' => $form->createView(),
        ]);

    }



  
    /**
     * Allow to add a video slide into a diapo pitch
     * 
     * @Route("/projects/{slug}/slideshow/add-video",name="slideshow_video_add")
     * 
     * @Security("is_granted('ROLE_USER') and user === pp.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function addVideoSlide (PPBasic $pp, Request $request, EntityManagerInterface $manager) {

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
            'presentation' => $pp,
            
        ]);

    }

    /**
     * Allow to Edit a Video Slide
     * 
     * @Route("/projects/{slug}/slideshow/edit-video/{id_slide}",name="slideshow_video_edit")
     * 
     *  @Entity("slide", expr="repository.find(id_slide)")
     * 
     * @Security("is_granted('ROLE_USER') and user === pp.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     * 
     * @return Response
     */
    public function editVideoSlide (PPBasic $pp, Slide $slide, Request $request, EntityManagerInterface $manager) {

        $form = $this->createForm(AddVideoSlideType::class, $slide);

        // Youtube Video Code Extraction

        $currentUrl = $slide->getUrl();
        $videoCode = substr( strrchr( $currentUrl, '/' ), 1 );
        $form->get('url')->setData($videoCode);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
      
            $videoUrl = 'https://www.youtube.com/embed/'.$slide->getUrl();

            $videoThumbnail = 'https://img.youtube.com/vi/'.$slide->getUrl().'/mqdefault.jpg';

            $slide->setUrl($videoUrl);
            $slide->setThumbnail($videoThumbnail);

            $manager->persist($slide);
            $manager->flush();

            $this->addFlash(
                'success',
                "La Diapo Vidéo a été Modifiée."
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);
        }

        return $this->render('slideshow/editVideo.html.twig', [

            'form' => $form->createView(),
            'slug' => $pp->getSlug(), 
            'presentation' => $pp,
            
        ]);

    }

}
