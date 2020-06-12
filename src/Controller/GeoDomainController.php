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
     * @Route("/manage-places", name="geodomains_places_manage")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas gérer les lieux de ce projet")
     * 
     */
    public function managePlaces(PPBasic $presentation)
    {
  
        return $this->render('geoDomains/manage_places.html.twig', [
            'presentation' => $presentation,
        ]);
    }

    /** 
     * @Route("/ajax-new-place", name="ajax_new_place") 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas ajouter des lieux à ce projet")
    */ 
    public function ajaxNewPlace(Request $request, PPBasic $presentation, EntityManagerInterface $manager) {

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

            $newPlace = new GeoDomain();
            $newPlace-> setPostalCode($postalCode)
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
            
            if (!$presentationCities->contains($newPlace)) {
                $presentation->addGeoDomain($newPlace);
    
                $manager->persist($newPlace);
                $manager->persist($presentation);
                $manager->flush();

                $lastIdProjectPlace = $newPlace
                ->getId();

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
     * Allow to remove a project place
     * 
     * @Route("/ajax-remove-place/", name="ajax_remove_place")
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
