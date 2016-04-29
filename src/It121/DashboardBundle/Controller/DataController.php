<?php

namespace It121\DashboardBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DataController extends DefaultController
{
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  DATA DASHBOARD ACTION **********************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * Data Dashboard view
     *
     */
    public function indexAction()
    {
        $options = array();

        $elementsForMenu = $this->getElementsForMenu();

        return $this->render('DashboardBundle:Data:index.html.twig', array_merge($options, $elementsForMenu));
    }


    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /********************************  Check Data Left ACTION *********************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Check Data Left
     * Calculating how long before data runs out
     *
     */
    public function checkDataLeftAction()
    {

        $em = $this->getDoctrine('doctrine')->getManager('calldev');

        //Get the dataLeft
        $dataLeft = $em->getRepository('CalldevBundle:DataLeft')->findAll();

        $dataLeft = array(
            "success" => (!empty($dataLeft)),
            "data" => $dataLeft
        );

        $serializer = SerializerBuilder::create()->build();
        $dataLeft = $serializer->serialize($dataLeft, 'json');

        $response = new Response($dataLeft);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
