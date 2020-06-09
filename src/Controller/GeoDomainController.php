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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/projects/{slug}/places")
 */
class GeoDomainController extends AbstractController
{

    /**
     * @Route("/manage-departments", name="geodomains_departments_manage")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas gérer les lieux de ce projet")
     * 
     */
    public function manageDepartments(PPBasic $presentation)
    {
  
        return $this->render('geoDomains/manage-departments.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /**
     * @Route("/new-city", name="geodomains_cities_manage")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas gérer les lieux de ce projet")
     * 
     */
    public function newCity(PPBasic $presentation)
    {
  
        return $this->render('geoDomains/manage-cities.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /** 
     * @Route("/ajaxNewCity", name="ajax_new_city") 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas ajouter des lieux à ce projet")
    */ 
    public function ajaxNewCity(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

        if ($request->isXmlHttpRequest()) {

            $geoType = $request->request->get('placeType');
            $placeName = $request->request->get('placeName');
            $latitude = $request->request->get('latitude');
            $longitude = $request->request->get('longitude');
            $postalCode = $request->request->get('postalCode');
            $cityName = $request->request->get('cityName');
            $country = $request->request->get('country');
            $route = $request->request->get('route');
            $sublocalityLevel1 = $request->request->get('sublocalityLevel1');
            $administrativeAreaLevel1 = $request->request->get('administrativeAreaLevel1');
            $administrativeAreaLevel2 = $request->request->get('administrativeAreaLevel2');

            //!!!!!!Faire une Validation sur le codePostal et le cityName

            $newCity = new GeoDomain();
            $newCity-> setPostalCode($postalCode)
                    -> setGeoType($geoType)
                    -> setPlaceName($placeName)
                    -> setLatitude($latitude)
                    -> setLongitude($longitude)
                    -> setCountry($country)
                    -> setAdministrativeAreaLevel1($administrativeAreaLevel1)
                    -> setAdministrativeAreaLevel2($administrativeAreaLevel2)
                    -> setSublocalityLevel1($sublocalityLevel1)
                    -> setRoute($route)
                    -> setCity($cityName);

            $presentationCities = $presentation->getGeoDomains();
            
            if (!$presentationCities->contains($newCity)) {
                $presentation->addGeoDomain($newCity);
    
                $manager->persist($newCity);
                $manager->persist($presentation);
                $manager->flush();

                $lastIdProjectPlace = $newCity->getId();

                $feedbackCode = true;
            }

            $dataResponse = [
                'administrativeAreaLevel1' => $administrativeAreaLevel1,
                'administrativeAreaLevel2' => $administrativeAreaLevel2,
                'sublocalityLevel1' => $sublocalityLevel1,
                'route' => $route,
                'country' => $country,
                'postalCode' => $postalCode,
                'cityName' => $cityName,
                'placeType' => $geoType,
                'placeName' => $placeName,
                'projectPlaceId' =>  $lastIdProjectPlace,
                'feedbackCode' => $feedbackCode,
            ];

            return new JsonResponse($dataResponse);
            
        }

    }


    /**
     * Permet de Supprimer une Ville dans une Présentation
     * 
     * @Route("/ajax-remove-city/", name="ajax_remove_city")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas supprimer des lieux de ce projet")
     * 
     * @return Response
     */
   public function delete(Request $request, PPBasic $presentation, EntityManagerInterface $manager, GeoDomainRepository $geoRepository){

        if ($request->isXmlHttpRequest()) {

            $projectPlaceId = $request->request->get('projectPlaceId');

            $projectPlace = $geoRepository->findOneById($projectPlaceId);

            $feedbackCode = false;

            if ($presentation->getGeoDomains()->contains($projectPlace)) {

                $presentation->removeGeoDomain($projectPlace);
                
                $manager->remove($projectPlace);

                $manager->persist($presentation);
                $manager->flush();

                $feedbackCode = true;
            }

            $dataResponse = [
                'projectPlaceId' =>  $projectPlaceId,
                'feedbackCode' => $feedbackCode,
            ];

            return new JsonResponse($dataResponse);

        }
   }


}
