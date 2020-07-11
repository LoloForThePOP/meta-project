<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    /**
     * Display Project Categories so that people can search project or needs by categories
     * 
     * @Route("/search-by-cat", name="searchByCatRepo")
    */

    public function searchByCategories(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('search/by_categories.html.twig', [
            'categories' => $categories,
        ]);
    }
   
    /** 
     * @Route("/ajax-search-by-cat", name="ajax_search_by_cat") 
    */ 

     public function ajaxSearchByCat(EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $jsonSelectedCategoriesIds = $request->request->get('selectedCategoriesIds');

            $selectedCategoriesIds = json_decode($jsonSelectedCategoriesIds,true);

            $result = $manager->createQuery('SELECT p.title, p.keywords, p.thumbnailName, p.goal, p.slug FROM App\Entity\PPBasic p WHERE p.isPublished=true ORDER BY p.createdAt DESC')->setMaxResults('10')->getResult();

            /* $category = $categoryRepository->findOneById($categoryId);
                
            $presentationCategories = $presentation->getCategories(); */

           /*  $arrData = [
                'catId' => $categoryId,
            ];

            return new JsonResponse($arrData); */

        }

    }

}
