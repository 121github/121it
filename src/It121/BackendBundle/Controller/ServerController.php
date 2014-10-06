<?php

namespace It121\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use It121\ServerBundle\Entity\Server;
use It121\BackendBundle\Util\Util;
use It121\ServerBundle\Form\ServerType;

class ServerController extends DefaultController
{
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  SERVER ACTION ******************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * Server List view
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ServerBundle:Server')->findAll();
        
        $options = array(
        		'entities' => $entities,
        );
        $elementsForMenu = $this->getElementsForMenu();
        
        return $this->render('BackendBundle:Server:index.html.twig', array_merge($options, $elementsForMenu));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW SERVER ACTION **************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Finds and displays a Server entity.
     *
     */
    public function showAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ServerBundle:Server')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Server entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:Server:show.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** CREATE SERVER ACTION ************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    
    /**
     * Creates a new Server entity.
     *
     */
    public function createAction(Request $request)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	$server = new Server();
    	$form = $this->createCreateForm($server);
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    		$eManager = $this->getDoctrine()->getManager();
    		 
    		Util::setCreateAuditFields($server, $userLogged->getId());
    		 
    		$eManager->persist($server);
    		$eManager->flush();
    
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Server Created!',
    						'message' => 'The server has been created'
    				)
    		);
    
    		$result = array(
    				'success' => true,
    				'error' => false,
    		);
    		$response = new Response(json_encode($result));
    		$response->headers->set('Content-Type', 'application/json');
    		 
    		return $response;
    
    	}
    
    	return $this->render('BackendBundle:Server:new.html.twig', array(
    			'entity' => $server,
    			'form'   => $form->createView(),
    	));
    }
    
    /**
     * Creates a form to create a Server entity.
     *
     * @param User $server The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Server $server)
    {
    	$form = $this->createForm(new ServerType(), $server, array(
    			'action' => $this->generateUrl('server_create'),
    			'method' => 'POST',
    	));
    
    	$form->add('submit', 'submit', array(
    			'label' => 'Create',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	));
    
    	return $form;
    }
    
    /**
     * Displays a form to create a new Server entity.
     *
     */
    public function newAction()
    {
    	$server = new Server();
    	$form   = $this->createCreateForm($server);
    
    	return $this->render('BackendBundle:Server:new.html.twig', array(
    			'entity' => $server,
    			'form'   => $form->createView(),
    	));
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** EDIT SERVER ACTION **************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to edit an existing Server entity.
     *
     */
    public function editAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ServerBundle:Server')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Server entity.');
    	}
    
    	$editForm = $this->createEditForm($entity);
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:Server:edit.html.twig', array(
    			'entity'      => $entity,
    			'edit_form'   => $editForm->createView(),
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Creates a form to edit a Server entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Server $entity)
    {
    	$form = $this->createForm(new ServerType(), $entity, array(
    			'action' => $this->generateUrl('server_update', array('id' => $entity->getId())),
    			'method' => 'PUT',
    	));
    
    	$form->add('submit', 'submit', array('label' => 'Update'));
    
    	return $form;
    }
    /**
     * Edits an existing Server entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    	 
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	 
    	$eManager = $this->getDoctrine()->getManager();
    
    	$server = $eManager->getRepository('ServerBundle:Server')->find($id);
    
    	if (!$server) {
    		throw $this->createNotFoundException('Unable to find Server entity.');
    	}
    
    	$editForm = $this->createEditForm($server);
    
    	$editForm->handleRequest($request);
    
    	$editForm->submit($request);
    	if ($editForm->isValid()) {
    
    		Util::setModifyAuditFields($server, $userLogged->getId());
    		 
    		$eManager->persist($server);
    		$eManager->flush();
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Server Changed!',
    						'message' => 'The project '.$server->getName().' has been updated'
    				)
    		);
    
    		$result = array(
    				'success' => true,
    				'error' => false,
    		);
    		$response = new Response(json_encode($result));
    		$response->headers->set('Content-Type', 'application/json');
    		 
    		return $response;
    	}
    	else {
    		return $this->render('BackendBundle:Server:edit.html.twig', array(
    				'entity'      => $server,
    				'edit_form'   => $editForm->createView(),
    		));
    	}
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** DELETE SERVER *******************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to remove an existing Server entity.
     *
     */
    public function removeAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ServerBundle:Server')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Server entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:Server:remove.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Deletes a Server entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$eManager = $this->getDoctrine()->getManager();
    	$server = $eManager->getRepository('ServerBundle:Server')->find($id);
    	 
    	if (!$server) {
    		throw $this->createNotFoundException('Unable to find Server entity.');
    	}
    	 
    	$form = $this->createDeleteForm($id);
    	$form->handleRequest($request);
    
    
    	if ($form->isValid()) {
    		 
    		$eManager->remove($server);
    		$eManager->flush();
    
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Server Deleted!',
    						'message' => 'The server '.$server->getName().' has been deleted'
    				)
    		);
    	}
    	else {
    		 
    		$this->get('session')->getFlashBag()->set(
    				'error',
    				array(
    						'alert' => 'error',
    						'title' => 'Error!',
    						'message' => 'The server '.$server->getName().' has NOT been deleted'
    				)
    		);
    	}
    
    	$result = array();
    	$response = new Response(json_encode($result));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    /**
     * Creates a form to delete a Server entity by id.
     *
     * @param mixed $serverId The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
    	return $this->createFormBuilder()
    	->setAction($this->generateUrl('server_delete', array('id' => $id)))
    	->setMethod('DELETE')
    	->add('submit', 'submit', array(
    			'label' => 'Delete',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	))
    	->getForm()
    	;
    }
}
