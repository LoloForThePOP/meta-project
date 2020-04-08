<?php

namespace App\Controller;

use App\Form\CityType;
use App\Entity\PPBasic;
use App\Entity\GeoDomain;
use App\Repository\GeoDomainRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/places")
 */
class PlaceController extends AbstractController
{
    /**
     * @Route("/", name="places_index")
     */
    public function index(PPBasic $presentation, GeoDomainRepository $geoRepo)
    {
        $geoPlaces = $geoRepo->findAll();

        return $this->render('places/index.html.twig', [
                'geoPlaces' => $geoPlaces,
                'presentation' => $presentation,
        ]);
    }

     /**
     * @Route("/new-city", name="places_new_city")
     */
    public function newCity(PPBasic $presentation)
    {
  
        return $this->render('places/new-city.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /** 
     * @Route("/ajaxNewCity", name="ajax_new_city") 
    */ 
    public function ajaxNewCity(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $postalCode = $request->request->get('postalCode');
            $cityName = $request->request->get('cityName');

            //!!!!!!Faire une Validation sur le codePostal et le cityName

            $newCity = new GeoDomain();
            $newCity-> setPostalCode($postalCode)
                    -> setCity($cityName);

            $presentationCities = $presentation->getGeoDomains();
            
            if (!$presentationCities->contains($newCity)) {
                $presentation->addGeoDomain($newCity);
    
                $manager->persist($newCity);
                $manager->persist($presentation);
                $manager->flush();

                $lastIdCityProject = $newCity->getId();

                $feedbackCode = true;
            }

            $dataResponse = [
                'postalCode' => $postalCode,
                'cityName' => $cityName,
                'idCityProject' =>  $lastIdCityProject,
                'feedbackCode' => $feedbackCode,
            ];

            return new JsonResponse($dataResponse);
            
        }

    }


    /**
     * Permet de Supprimer une Ville dans une Présentation
     * 
     * @Route("/ajaxRemoveCity/", name="ajax_remove_city")
     * 
     * @return Response
     */
   public function delete(Request $request, PPBasic $presentation, EntityManagerInterface $manager, GeoDomainRepository $geoRepository){

        if ($request->isXmlHttpRequest()) {

            $idCityProject = $request->request->get('idCityProject');
            $cityName = $request->request->get('cityName');

            $cityProject = $geoRepository->findOneById($idCityProject);

            $feedbackCode = false;

            if ($presentation->getGeoDomains()->contains($cityProject)) {

                $presentation->removeGeoDomain($cityProject);
                
                $manager->remove($cityProject);

                $manager->persist($presentation);
                $manager->flush();

                $feedbackCode = true;
            }

            $dataResponse = [
                'idCityProject' =>  $idCityProject,
                'cityName' =>  $cityName,
                'feedbackCode' => $feedbackCode,
            ];

            return new JsonResponse($dataResponse);

        }
   }


}
