<?php

namespace App\Controller;

use App\Entity\PPBasic;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/categories")
 */
class CategoriesController extends AbstractController
{
    /**
     * @Route("/", name="index_categories")
     */
    public function index(PPBasic $presentation, CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories,
            'presentation' => $presentation,
        ]);
        
    }


    /** 
     * @Route("/ajaxUpdateCategory", name="ajax_update_category") 
    */ 
    public function ajaxUpdateCategory(Request $request, PPBasic $presentation, CategoryRepository $categoryRepository, EntityManagerInterface $manager) {

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

            return new Response();

            /* $arrData = [
                'catId' => $categoryId,
            ];

            return new JsonResponse($arrData); */

        }

    }
}
