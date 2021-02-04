<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Entity\PPBasic;
use App\Repository\NewsRepository;
use App\Entity\PPMajorLogs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/projects/{slug}/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/", name="manage_news")
     * 
     */
    public function index(PPBasic $presentation): Response
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

        return $this->render('news/index.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /**
     * @Route("/create", name="create_news")
     */
    public function create(PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

        $news = new News ();

        $form = $this->createForm(NewsType::class, $news);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $news->setProject($presentation);

            $manager->persist($news);

            $manager->flush();

            $idNews=$news->getId();

            PPMajorLogs::updateLogs($presentation, 'news', 'new', $idNews, $manager);

            $this->addFlash(
                'success',
                "L'actualité a été ajoutée"
            );

            return $this->redirectToRoute('manage_news', [
                'slug' => $presentation->getSlug(),
            ]);

        }
    
        return $this->render('news/new.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
        ]);
    }

    /**
     * @Route("/edit/{idNews}", name="edit_news")
     */
    public function edit($idNews, NewsRepository $newsRepository, PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('edit', $presentation);

        $news = $newsRepository->findOneById($idNews);

        $form = $this->createForm(NewsType::class, $news);
    
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $news->setProject($presentation);
            
            $manager->persist($news);

            $manager->flush();

            $this->addFlash(
                'success',
                "L'actualité a été modifiée"
            );

            return $this->redirectToRoute('manage_news', [
                'slug' => $presentation->getSlug(),
            ]);

        }
    
        return $this->render('news/edit.html.twig', [
            'form' => $form->createView(),
            'slug' => $presentation->getSlug(),
            'news' => $news,
        ]);
    }

    /** 
     * Allow to Get News Details with an ajax request
     * 
     * @Route("/ajaxGetNewsDetails", name="ajax_get_news_details")
     * 
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

    
    /**
     * @Route("/{idNews}/delete", name="delete_news")
     * 
     * @Entity("news", expr="repository.find(idNews)")
     * 
     */
    public function delete($slug, News $news, NewsRepository $newsRepository, Request $request): Response
    {
        
        $this->denyAccessUnlessGranted('edit', $news->getProject());
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($news);
        $entityManager->flush();

        return $this->redirectToRoute('manage_news',[
            'slug' => $slug,
        ]);
    }


    /**
     * @Route("/news/ajax-show-embed", name="show_embed_news")
     * 
     */
    public function showEmbed(Request $request, NewsRepository $newsRepository)
    {
        
        if ($request->isXmlHttpRequest()) {

            //get selected news

            $idNews = $request->request->get('idEntity');

            $news = $newsRepository->findOneById($idNews);

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'news/show_embed.html.twig', 

                    [
                        'news' => $news,
                    ]
                ),
            ];

            //dump($dataResponse);

            return new JsonResponse($dataResponse);

        
        }

    }


}
