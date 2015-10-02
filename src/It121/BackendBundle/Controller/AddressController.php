<?php

namespace It121\BackendBundle\Controller;

use It121\AddressBundle\Entity\ImportPaf;
use It121\AddressBundle\Form\ImportPafType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * User controller.
 *
 */
class AddressController extends DefaultController
{
	
    /******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  ADDRESS ACTION ********************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * User List view
     *
     */
    public function indexAction(Request $request = null)
    {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        //$entities = $emukpostcodes->getRepository('AddressBundle:PafPostcode')->findAll();
        //$this->get('logger')->info(var_export($entities, true));

        $dql   = "SELECT p FROM AddressBundle:PafPostcode p ORDER BY p.id ASC";
        $query = $emukpostcodes->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $entities = $paginator->paginate(
            $query,
            $request->query->get('page', 1), /* page number */
            100 /* limit per page */
        );

        $options = array(
        		'entities' => $entities,
        );
        $elementsForMenu = $this->getElementsForMenu();

        return $this->render('BackendBundle:Address:index.html.twig', array_merge($options, $elementsForMenu));
    }

    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW ADDRESS ACTION ****************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Finds and displays a Address entity.
     *
     */
    public function pafShowAction($id)
    {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        $entity = $emukpostcodes->getRepository('AddressBundle:PafPostcode')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PafPostcode entity.');
        }

        return $this->render('BackendBundle:Address:pafShow.html.twig', array(
            'address'      => $entity,
        ));
    }


    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** UPDATE PAF ADDRESSES ACTION ****************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Update the PAF Addresses from a csv file
     *
     */
    public function pafUpdateAction()
    {
        $import = new ImportPaf();
        $importForm = $this->createImportForm($import);

        return $this->render('BackendBundle:Address:pafUpdate.html.twig', array(
            'entity' => $import,
            'import_form' => $importForm->createView(),
        ));
    }

    /**
     * Creates a form to import the PAF addresses from a csv file.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createImportForm(ImportPaf $import)
    {
        $form = $this->createForm(new ImportPafType(), $import, array(
            'action' => $this->generateUrl('address_paf_import'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
            'label' => 'Import',
            'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
        ));

        return $form;
    }


    /**
     * Creates a new User entity.
     *
     */
    public function pafImportAction(Request $request)
    {
        $userLogged = $this->get('security.context')->getToken()->getUser();

        if (!$userLogged) {
            throw $this->createNotFoundException('Unable to find this user.');
        }

        $import = new ImportPaf();
        $form = $this->createImportForm($import);
        $form->handleRequest($request);

        $this->get('logger')->info(var_export($import->getFilePath(), true));

        if ($form->isValid()) {
//            $eManager = $this->getDoctrine()->getManager();
//
//            Util::setCreateAuditFields($user, $userLogged->getId());
//
//            //Save the EmpDetail
//            $userDetail = $user->getUserDetail();
//            $userDetail->setUser($user);
//
//            Util::setCreateAuditFields($userDetail, $userLogged->getId());
//
//
//            $eManager->persist($user);
//            $eManager->persist($userDetail);
//            $eManager->flush();


//            $this->get('session')->getFlashBag()->set(
//                'success',
//                array(
//                    'alert' => 'success',
//                    'title' => 'Addresses Imported!',
//                    'message' => 'The addresses has been imported'
//                )
//            );
//
//            $result = array(
//                'success' => true,
//                'error' => false,
//            );
//            $response = new Response(json_encode($result));
//            $response->headers->set('Content-Type', 'application/json');
//
//            return $response;

        }




        return $this->render('BackendBundle:Address:pafUpdate.html.twig', array(
            'entity' => $import,
            'import_form'   => $form->createView(),
        ));
    }

}
