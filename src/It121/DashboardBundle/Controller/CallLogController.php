<?php

namespace It121\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CallLogController extends DefaultController
{
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  CALL LOG ACTION ****************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * Call Log List view
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LogBundle:CallLog')->findBy(
            array(),
            array('callDate' => 'DESC'),
            100,
            0
        );

        
        $options = array(
        		'entities' => $entities,
        );
        $elementsForMenu = $this->getElementsForMenu();
        
        return $this->render('DashboardBundle:CallLog:index.html.twig', array_merge($options, $elementsForMenu));
    }
}
