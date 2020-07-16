<?php

namespace App\Controller;

use App\Repository\PPBasicRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{

    /**
     * Display Project Categories so that people can search project or needs by categories
     * 
     * @Route("/search-by-cat", name="search_by_cat")
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

     public function ajaxSearchByCat(PPBasicRepository $repo, Request $request) {

        if ($request->isXmlHttpRequest()) {

            //get selected categories

            $jsonSelectedCategoriesIds = $request->request->get('jsonSelectedCategoriesIds');

            $selectedCategoriesIds = json_decode($jsonSelectedCategoriesIds,true);

            //count selected categories

            $countSelectedCategories = $request->request->get('countSelectedCategories');

            //maximum categories allowed

            //$maxCategories =  $countSelectedCategories + 2;

            //get search results

            $results = $repo->findByCategories($selectedCategoriesIds);

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'search/by_categories_results.html.twig', 

                    [
                        'results' => $results,
                    ]
                ),
            ];

            //dump($dataResponse);

            return new JsonResponse($dataResponse);

        
        }

    }

}
