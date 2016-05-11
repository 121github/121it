<?php

namespace It121\DashboardBundle\Controller;

use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\Response;

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

    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /********************************  Check Outcomes ACTION *********************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Check Data Left
     * Calculating how long before data runs out
     *
     */
    public function checkOutcomesAction()
    {
        $em = $this->getDoctrine('doctrine')->getManager('calldev');

        $campaign = $this->get('request')->request->get('campaign');

        $date_from = $this->get('request')->request->get('date_from');
        $date_to = $this->get('request')->request->get('date_to');
        $current_date = date('Y-m-d');

        //Live tables
        if ($date_from == $current_date && $date_to = $current_date) {
            $campaign = "live-".$campaign;
            //Set the table name
            $em->getClassMetadata('CalldevBundle:LiveRecord')->setTableName($campaign);
            //Get the outcomes
            $outcomes = $em->getRepository('CalldevBundle:LiveRecord')->getOutcomes($date_from, $date_to);
        }
        //Historical tables
        else {
            $campaign = "histcomp-".$campaign;
            //Set the table name
            $em->getClassMetadata('CalldevBundle:HistRecord')->setTableName($campaign);
            //Get the outcomes
            $outcomes = $em->getRepository('CalldevBundle:HistRecord')->getOutcomes($date_from, $date_to);
        }

        $result = array();
        $columns = array();
        foreach ($outcomes as $outcome) {

            $result['outcomes'][$outcome['outcome']][$outcome['prefix']] = $outcome['num'];

            if (!isset($result['total'][$outcome['prefix']])) {
                $result['total'][$outcome['prefix']] = 0;
            }
            $result['total'][$outcome['prefix']] += $outcome['num'];



            if(!in_array($outcome['prefix'], $columns)){
                array_push($columns, $outcome['prefix']);
            }
        }

        $outcomes = array(
            "success" => (!empty($outcomes)),
            "columns" => $columns,
            "data" => $result
        );

        $serializer = SerializerBuilder::create()->build();
        $outcomes = $serializer->serialize($outcomes, 'json');

        $response = new Response($outcomes);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
