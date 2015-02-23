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
    public function indexAction(Request $request = null)
    {
        $em = $this->getDoctrine()->getManager();


        $dql   = "SELECT a FROM LogBundle:CallLog a ORDER BY a.callDate DESC";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            $request->query->get('page', 1), /* page number */
            100 /* limit per page */
        );
        
        $options = array(
                'entities' => $entities
        );
        $elementsForMenu = $this->getElementsForMenu();
        
        return $this->render('DashboardBundle:CallLog:index.html.twig', array_merge($options, $elementsForMenu));
    }
}
