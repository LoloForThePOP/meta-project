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
     * Search by categories : 
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

    /**
     * Search by Places
     * 
     * @Route("/search-by-places", name="search_by_places")
    */

    public function searchByPlaces()
    {

        return $this->render('search/by_places.html.twig', [
            
        ]);
    }

    
    /** 
     * @Route("/ajax-search-by-places", name="ajax_search_by_places") 
     *
    */ 
    public function ajaxSearchByPlaces(Request $request, PPBasicRepository $repo) {

        if ($request->isXmlHttpRequest()) {

            // type of Place user has given (ex: locality; country; etc (see below))
            $geoType = $request->request->get('placeType');

            // name of Place user has given (ex: France; London; etc)
            $placeName = $request->request->get('placeName');

            // we possibly enlarge search result: we possibly broaden user input: Names of places which contain the user input:
            
            // sublocalityLevel1 = smaller than a city
            $sublocalityLevel1 = $request->request->get('sublocalityLevel1');
            
            // city = locality: contains a set of sublocalityLevel1
            $city = $request->request->get('cityName');

            // administrativeAreaLevel2 = contains a set of cities
            $administrativeAreaLevel2 = $request->request->get('administrativeAreaLevel2');

            // administrativeAreaLevel1 = contains a set of administrativeAreaLevel2
            $administrativeAreaLevel1 = $request->request->get('administrativeAreaLevel1');

            // country = contains a set of administrativeAreaLevel1
            $country = $request->request->get('country');

            dump($placeName);
           

            $results = $repo->findByPlaces($placeName, $city, $administrativeAreaLevel2, $administrativeAreaLevel1);

            $dataResponse = [

                'html' => $this->renderView(
                    
                    'search/by_places_results.html.twig', 

                    [
                        'results' => $results,
                    ]
                ),
            ];

            return new JsonResponse($dataResponse);
            
        }

    }







}
