<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use App\Entity\Location;
use App\Form\LocationType;

/**
 * Rest controller.
 *
 * @Route("/")
 */
class GeoController extends Controller
{
    /**
     * @Route("/api/geo", name="api_geo")
     */
    public function index()
    {
        return $this->render('api/geo/index.html.twig', [
            'controller_name' => 'GeoController',
        ]);
    }

    /**
     * Return.
     * @FOSRest\Get("/location/{id}", name="get_location")
     *
     * @return array
     */
    public function getLocation($id)
    {

        $location = $this->getDoctrine()
            ->getRepository(Location::class)->findLocationWithRelated($id);

//$view = $this->view($location, 200)
//            ->setTemplate("MyBundle:Location:getLocationss.html.twig")
//            ->setTemplateVar('location');

        return $this->json($location);
    }

     /**
     * Return id of created location.
     * @FOSRest\Post("/location/")
     * @param Request $request the request object
     * @return array
     */
    public function addLocation(Request $request)
    {
        $location = new Location();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(LocationType::class, $location);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($location);
            $em->flush();

            return $this->redirectToRoute('get_location', array('id' => $location->getId()));

        }
        return $this->json([
                'message' => '',
                'path' => 'src/Controller/Api/GeoController.php',
        ]);
    }
}
