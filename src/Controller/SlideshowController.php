<?php



namespace App\Controller;


use App\Entity\Slide;
use App\Entity\PPBasic;
use App\Form\SlideshowType;
use App\Form\AddVideoSlideType;
use App\Form\SlideshowImagesType;
use App\Service\ImageEditService;
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
    public function index(PPBasic $pp, Request $request, EntityManagerInterface $manager, ImageEditService $editImageService)
    {        

        //Add an Image Form
        
        $addImageForm = $this->createForm(SlideshowImagesType::class);

        $addImageForm->handleRequest($request);

        if ($addImageForm->isSubmitted() && $addImageForm->isValid()){

            $slide = $addImageForm->getData();

            // count previous slides in order to set a position to the new image slide
            $countPreviousSlides = count($pp->getSlides());
            $slide->setPosition($countPreviousSlides);

            $slide->setMediaType("image");

            $pp->addSlide($slide);
            
            $manager->persist($slide);
            $manager->persist($pp);
            $manager->flush();
            
            $editImageService->edit('presentation_slide',$slide->getSlideName());


            /* $image = new ImageResize('images/projects/slides_images/'.$slide->getSlideName());
            $image->resizeToWidth(700);
            $image->save('images/projects/slides_images/'.$slide->getSlideName()); */
            
            $this->addFlash(
                'success',
                "L'image a été ajoutée"
            );

            return $this->redirectToRoute('slideshow_index', [
                'slug' => $pp->getSlug(),
            ]);

        }
        return $this->render('slideshow/index.html.twig', [
            'slug' => $pp->getSlug(),
            'presentation' => $pp,
            'addImageForm' => $addImageForm->createView(),
        ]);
    }
    
    /**
     * Allow to update slideshow slides order
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
                "L'image a été mise à jour"
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
     * Allow to add a video slide into a slideshow
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
            $countPreviousSlides = count($pp->getSlides());

            $newSlidePosition = $countPreviousSlides;
      
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
                "La vidéo a bien été ajoutée"
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
                "La vidéo a bien été modifiée"
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
