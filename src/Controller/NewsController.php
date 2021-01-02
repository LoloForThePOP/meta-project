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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
    public function index(PPBasic $presentation): Response
    {
        return $this->render('news/index.html.twig', [
            'presentation' => $presentation,
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
     * @Route("/edit/{idNews}", name="edit_news")
     */
    public function edit($idNews, NewsRepository $newsRepository, PPBasic $presentation, Request $request, EntityManagerInterface $manager): Response
    {
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

            return $this->redirectToRoute('index_news', [
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
     * @Security("is_granted('ROLE_USER') and user === news.getProject().getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas la modifier")
     */
    public function delete($slug, News $news, NewsRepository $newsRepository, Request $request): Response
    {
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($news);
        $entityManager->flush();

        return $this->redirectToRoute('index_news',[
            'slug' => $slug,
        ]);
    }


}
