<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * Display Project Categories so that people can search project or needs by categories
     * 
     * @Route("/search-by-cat", name="searchByCatRepo")
     */
    public function searchByCatRepo(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('search/by-cat.html.twig', [
            'categories' => $categories,
        ]);
    }


    /** 
     * @Route("/ajax-search-by-cat", name="ajax_search_by_cat") 
    */ 
    /* public function ajaxSearchByCat(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $categoryId = $request->request->get('catId');

            $category = $categoryRepository->findOneById($categoryId);
                
            $presentationCategories = $presentation->getCategories();

            if (!$presentationCategories->contains($category)) {
                $presentation->addCategories($category);
            }else{
                $presentation->removeCategories($category);
            }

            $manager->persist($presentation);
            $manager->flush();

            $arrData = [
                'catId' => $categoryId,
            ];

            return new JsonResponse($arrData);

        }

    } */

}
