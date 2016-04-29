<?php

namespace It121\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use It121\ServerBundle\Entity\Server;
use It121\BackendBundle\Util\Util;
use It121\ServerBundle\Form\ServerType;
use Symfony\Component\HttpFoundation\JsonResponse;

class DataController extends DefaultController
{
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  DATA ACTION ******************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * Server List view
     *
     */
    public function indexAction()
    {
        $emcalldev = $this->getDoctrine('doctrine')->getManager('calldev');

        $dataLeft = $emcalldev->getRepository('CalldevBundle:DataLeft')->findAll();

        $options = array(
            'dataLeft' => $dataLeft
        );
        $elementsForMenu = $this->getElementsForMenu();

        return $this->render('BackendBundle:Data:index.html.twig', array_merge($options, $elementsForMenu));
    }
}
