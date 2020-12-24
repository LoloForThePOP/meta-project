<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Entity\PPBasic;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/** 
 * 
 * @Route("/projects/{slug}/news")
 * 
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/", name="index_news")
     */
    public function index(): Response
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
        ]);
    }

    /**
     * @Route("/create", name="create_news")
     */
    public function create(PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {
        $news = new News ();

        $form = $this->createForm(NewsType::class, $news);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $news->setProject($presentation);

            $manager->persist($news);

            $manager->flush();

            $this->addFlash(
                'success',
                "L'actualité a été ajoutée"
            );

            return $this->redirectToRoute('index_news', [
                'slug' => $presentation->getSlug(),
            ]);

        }
    
        return $this->render('news/new.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

    /** 
     * Allow to Get News Details with an ajax request
     * 
     * @Route("/ajaxGetNewsDetails", name="ajax_get_news_details") 
     *
    */ 
 
    public function ajaxNewsDetails(Request $request, NewsRepository $newsRepository, EntityManagerInterface $manager, PPBasic $presentation) {

        if ($request->isXmlHttpRequest()) {

            $newsId = $request->request->get('newsId');

            $news = $newsRepository->findOneById($newsId);

            $newsTextContent = $news->getTextContent();

            $dataResponse = [

                'newsTextContent' => $newsTextContent,
                
            ];

            return new JsonResponse($dataResponse);
            
        }

    }
}
