<?php

namespace It121\BackendBundle\Controller;

use It121\AddressBundle\Entity\ImportPaf;
use It121\AddressBundle\Entity\PostcodeIo;
use It121\AddressBundle\Form\ImportPafType;
use JMS\Serializer\SerializerBuilder;
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
	/********************************  ADDRESS ACTION ******************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * PAF Addresses List view
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

    /**
     * Open Addresses List view
     *
     */
    public function openAddressAction(Request $request = null)
    {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        $dql   = "SELECT p FROM AddressBundle:OpenPostcode p ORDER BY p.id ASC";
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

        return $this->render('BackendBundle:Address:openAddress.html.twig', array_merge($options, $elementsForMenu));
    }

    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW ADDRESS ACTION *************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Finds and displays a PAF Address entity.
     *
     */
    public function pafShowAction($id)
    {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        $pafPostcode = $emukpostcodes->getRepository('AddressBundle:PafPostcode')->find($id);

        if (!$pafPostcode) {
            throw $this->createNotFoundException('Unable to find PafPostcode entity.');
        }

        return $this->render('BackendBundle:Address:pafShow.html.twig', array(
            'address'      => $pafPostcode,
        ));
    }

    /**
     * Finds and displays a PAF Address entity.
     *
     */
    public function openShowAction($id)
    {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        $openPostcode = $emukpostcodes->getRepository('AddressBundle:OpenPostcode')->find($id);

        if (!$openPostcode) {
            throw $this->createNotFoundException('Unable to find OpenPostcode entity.');
        }

        return $this->render('BackendBundle:Address:openShow.html.twig', array(
            'address'      => $openPostcode,
        ));
    }

    /**
     * Get postcodeIo details
     *
     */
    public function getPostcodeIoAction(Request $request)
    {
        $postcode = $request->get('postcode');
        $pafPostcodeId = $request->get('pafPostcodeId');
        $openPostcodeId = $request->get('openPostcodeId');

        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');

        $data = array(
            "success" => false,
            "data" => array()
        );

        if ($pafPostcodeId) {
            $pafPostcode = $emukpostcodes->getRepository('AddressBundle:PafPostcode')->find($pafPostcodeId);
            if (!$pafPostcode) {
                throw $this->createNotFoundException('Unable to find PafPostcode entity.');
            }
            $postcodeIoId = ($pafPostcode->getPostcodeIo()?$pafPostcode->getPostcodeIo()->getId():null);
            $postcodeIo = $this->getPostcodeIo($postcode, $postcodeIoId);
            $pafPostcode->setPostcodeIo($postcodeIo);

            $emukpostcodes->persist($pafPostcode);
            $emukpostcodes->flush();

            $data = array(
                "success" => (!empty($pafPostcode)),
                "data" => $pafPostcode
            );
        }
        else if ($openPostcodeId) {
            $openPostcode = $emukpostcodes->getRepository('AddressBundle:OpenPostcode')->find($openPostcodeId);
            if (!$openPostcode) {
                throw $this->createNotFoundException('Unable to find OpenPostcode entity.');
            }

            $postcodeIoId = ($openPostcode->getPostcodeIo()?$openPostcode->getPostcodeIo()->getId():null);
            $postcodeIo = $this->getPostcodeIo($postcode, $postcodeIoId);
            $openPostcode->setPostcodeIo($postcodeIo);

            $openPostcode->setPostcodeIo($postcodeIo);
            $emukpostcodes->persist($openPostcode);
            $emukpostcodes->flush();

            $data = array(
                "success" => (!empty($openPostcode)),
                "data" => $openPostcode
            );
        }


        $serializer = SerializerBuilder::create()->build();
        $data = $serializer->serialize($data, 'json');

        $response = new Response($data);

        $response->headers->set('Content-Type', 'application/json');


        return $response;
    }

    /**
     * Get PostcodeIo
     */
    private function getPostcodeIo($postcode, $postcodeIoId = null) {
        $emukpostcodes = $this->getDoctrine('doctrine')->getManager('uk_postcodes');
        $client = $this->container->get('box_uk_postcodes_io.client');

        if ($postcodeIoId) {
            $postcodeIo = $emukpostcodes->getRepository('AddressBundle:PostcodeIo')->find($postcodeIoId);
        }
        else {
            $postcodeIo = new PostcodeIo();
        }

        try{
            $response = $client->lookup(array('postcode' => $postcode));

            $postcodeIo->setPostcode($response['result']['postcode']);
            $postcodeIo->setQuality($response['result']['quality']);
            $postcodeIo->setEastings($response['result']['eastings']);
            $postcodeIo->setNorthings($response['result']['northings']);
            $postcodeIo->setCountry($response['result']['country']);
            $postcodeIo->setNhsHa($response['result']['nhs_ha']);
            $postcodeIo->setLongitude($response['result']['longitude']);
            $postcodeIo->setLatitude($response['result']['latitude']);
            $postcodeIo->setParliamentaryConstituency($response['result']['parliamentary_constituency']);
            $postcodeIo->setEuropeanElectoralRegion($response['result']['european_electoral_region']);
            $postcodeIo->setPrimaryCareTrust($response['result']['primary_care_trust']);
            $postcodeIo->setRegion($response['result']['region']);
            $postcodeIo->setLsoa($response['result']['lsoa']);
            $postcodeIo->setMsoa($response['result']['msoa']);
            $postcodeIo->setIncode($response['result']['incode']);
            $postcodeIo->setOutcode($response['result']['outcode']);
            $postcodeIo->setAdminDistrict($response['result']['admin_district']);
            $postcodeIo->setParish($response['result']['parish']);
            $postcodeIo->setAdminCounty($response['result']['admin_county']);
            $postcodeIo->setAdminWard($response['result']['admin_ward']);
            $postcodeIo->setCcg($response['result']['ccg']);
            $postcodeIo->setNuts($response['result']['nuts']);

            $emukpostcodes->persist($postcodeIo);

        } catch(\Exception $e){

        }

        return $postcodeIo;
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
