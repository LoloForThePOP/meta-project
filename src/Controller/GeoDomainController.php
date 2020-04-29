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
     * @Route("/ajax-remove-city/", name="ajax_remove_city")
     * 
     *  @Security("is_granted('ROLE_USER') and user === presentation.getCreator()", message="Cette présentation ne vous appartient pas, vous ne pouvez pas supprimer des lieux de ce projet")
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
