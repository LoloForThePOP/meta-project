<?php

namespace App\Controller;

use App\Form\SearchTextType;
use App\Repository\PPBasicRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function ajaxSearchByPlaces(Request $request, PPBasicRepository $repo, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            // type of Place user has given (ex: locality; country; etc (see below))
            $geoType = $request->request->get('placeType');

            // name of Place user has given (ex: France; London; etc)
            $placeName = $request->request->get('placeName');

            // latitude and longitude of Place user has given
            $latitude = $request->request->get('latitude');
            $longitude = $request->request->get('longitude');

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

            if ($geoType=='locality' OR $geoType=='sublocality_level_1') {
                
                $radius = 100; // kilometers

                $parameters = array(
                    'latitude' => $latitude, 
                    'longitude' => $longitude,
                    'radius' => $radius
                );

                $results = $manager->createQuery(
                    
                    'SELECT p as project, g, ( ACOS( COS( RADIANS( :latitude  ) ) 
                    * COS( RADIANS( g.latitude ) )
                    * COS( RADIANS( g.longitude ) - RADIANS( :longitude ) )
                    + SIN( RADIANS( :latitude  ) )
                    * SIN( RADIANS( g.latitude ) )
                    ) * 6371 ) AS distance_in_km
                    FROM App\Entity\PPBasic p
                    JOIN p.geoDomains g
                    HAVING distance_in_km <= :radius
                    ORDER BY distance_in_km ASC')->setParameters($parameters)->setMaxResults('10')->getResult();

            }
            else {

                $results = $repo->findByPlaces($geoType, $placeName);
         
            }
            

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

    
    /**
     * Search by Text
     * 
     * @Route("/search-by-text", name="search_by_text")
    */

    public function searchByText(PPBasicRepository $repo, Request $request)
    {

        $searchForm = $this->createForm(SearchTextType::class);

        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {

            $results = $repo->findByText($searchForm->get('words')->getData());

            dd($results);

            return $this->redirectToRoute('search_by_text');
        }

        return $this->render('search/by_text.html.twig', [
            'searchForm' => $searchForm->createView(),
            
        ]);
    }

       
    /** 
     * @Route("/ajax-search-by-text", name="ajax_search_by_text", methods={"GET"})) 
     *
     * @param Request $request Instance de Request
     *
     * @return JsonResponse
     */
    public function ajaxSearchByText(PPBasicRepository $repo, Request $request) {

        $userString = $request->get('searchByTextInput');

        //dump($userString);
        //$userString = 'follow';

        if ($request->isXmlHttpRequest()) {

            $results = $repo->findByText($userString);

            $categories[] = ['result' => 'Objectifs de projets', 'url' => 'lol'];

            foreach ($results as $result) {
                $categories[] = [
                    'result' => $result['presentation']->getGoal(),
                    'url' => 'tip',
                ];
            }

            //dd($results);


           

               
            return new JsonResponse($categories);

        }

        else{
            return new Response(
                var_dump($userString).'There are no jobs in the database', 
                 Response::HTTP_OK
            );
        }

        

    }









}
