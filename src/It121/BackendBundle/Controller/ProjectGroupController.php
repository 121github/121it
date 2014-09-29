<?php

namespace It121\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use It121\ServerBundle\Entity\Server;
use It121\BackendBundle\Util\Util;
use It121\ProjectBundle\Entity\ProjectGroup;
use It121\ProjectBundle\Form\ProjectGroupType;

class ProjectGroupController extends Controller
{
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  PROJECT GROUP ACTION ***********************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * ProjectGroup List view
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ProjectBundle:ProjectGroup')->findAll();

        return $this->render('BackendBundle:ProjectGroup:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW PROJECT GROUP ACTION *******************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Finds and displays a ProjectGroup entity.
     *
     */
    public function showAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:ProjectGroup')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find ProjectGroup Group entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:ProjectGroup:show.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** CREATE PROJECT GROUP ACTION *****************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    
    /**
     * Creates a new ProjectGroup entity.
     *
     */
    public function createAction(Request $request)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	$projectGroup = new ProjectGroup();
    	$form = $this->createCreateForm($projectGroup);
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    		$eManager = $this->getDoctrine()->getManager();
    		 
    		Util::setCreateAuditFields($projectGroup, $userLogged->getId());
    		 
    		$eManager->persist($projectGroup);
    		$eManager->flush();
    
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'ProjectGroup Created!',
    						'message' => 'The project has been created'
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
    
    	return $this->render('BackendBundle:ProjectGroup:new.html.twig', array(
    			'entity' => $projectGroup,
    			'form'   => $form->createView(),
    	));
    }
    
    /**
     * Creates a form to create a ProjectGroup entity.
     *
     * @param User $projectGroup The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProjectGroup $projectGroup)
    {
    	$form = $this->createForm(new ProjectGroupType(), $projectGroup, array(
    			'action' => $this->generateUrl('projectgroup_create'),
    			'method' => 'POST',
    	));
    
    	$form->add('submit', 'submit', array(
    			'label' => 'Create',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	));
    
    	return $form;
    }
    
    /**
     * Displays a form to create a new ProjectGroup entity.
     *
     */
    public function newAction()
    {
    	$projectGroup = new ProjectGroup();
    	$form   = $this->createCreateForm($projectGroup);
    
    	return $this->render('BackendBundle:ProjectGroup:new.html.twig', array(
    			'entity' => $projectGroup,
    			'form'   => $form->createView(),
    	));
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** EDIT PROJECT GROUP ACTION *******************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to edit an existing ProjectGroup entity.
     *
     */
    public function editAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:ProjectGroup')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find ProjectGroup entity.');
    	}
    
    	$editForm = $this->createEditForm($entity);
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:ProjectGroup:edit.html.twig', array(
    			'entity'      => $entity,
    			'edit_form'   => $editForm->createView(),
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Creates a form to edit a ProjectGroup entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ProjectGroup $entity)
    {
    	$form = $this->createForm(new ProjectGroupType(), $entity, array(
    			'action' => $this->generateUrl('projectgroup_update', array('id' => $entity->getId())),
    			'method' => 'PUT',
    	));
    
    	$form->add('submit', 'submit', array('label' => 'Update'));
    
    	return $form;
    }
    /**
     * Edits an existing ProjectGroup entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    	 
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	 
    	$eManager = $this->getDoctrine()->getManager();
    
    	$projectGroup = $eManager->getRepository('ProjectBundle:ProjectGroup')->find($id);
    
    	if (!$projectGroup) {
    		throw $this->createNotFoundException('Unable to find ProjectGroup entity.');
    	}
    
    	$editForm = $this->createEditForm($projectGroup);
    
    	$editForm->handleRequest($request);
    
    	$editForm->submit($request);
    	if ($editForm->isValid()) {
    		Util::setModifyAuditFields($projectGroup, $userLogged->getId());
    		 
    		$eManager->persist($projectGroup);
    		$eManager->flush();
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'ProjectGroup Changed!',
    						'message' => 'The project '.$projectGroup->getName().' has been updated'
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
    		return $this->render('BackendBundle:ProjectGroup:edit.html.twig', array(
    				'entity'      => $projectGroup,
    				'edit_form'   => $editForm->createView(),
    		));
    	}
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** DELETE GROUP PROJECT ************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to remove an existing ProjectGroup entity.
     *
     */
    public function removeAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:ProjectGroup')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find ProjectGroup entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:ProjectGroup:remove.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Deletes a ProjectGroup entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$eManager = $this->getDoctrine()->getManager();
    	$projectGroup = $eManager->getRepository('ProjectBundle:ProjectGroup')->find($id);
    	 
    	if (!$projectGroup) {
    		throw $this->createNotFoundException('Unable to find ProjectGroup entity.');
    	}
    	 
    	$form = $this->createDeleteForm($id);
    	$form->handleRequest($request);
    
    
    	if ($form->isValid()) {
    		 
    		$eManager->remove($projectGroup);
    		$eManager->flush();
    
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'ProjectGroup Deleted!',
    						'message' => 'The project '.$projectGroup->getName().' has been deleted'
    				)
    		);
    	}
    	else {
    		 
    		$this->get('session')->getFlashBag()->set(
    				'error',
    				array(
    						'alert' => 'error',
    						'title' => 'Error!',
    						'message' => 'The project '.$projectGroup->getName().' has NOT been deleted'
    				)
    		);
    	}
    
    	$result = array();
    	$response = new Response(json_encode($result));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    /**
     * Creates a form to delete a ProjectGroup entity by id.
     *
     * @param mixed $projectGroupId The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
    	return $this->createFormBuilder()
    	->setAction($this->generateUrl('projectgroup_delete', array('id' => $id)))
    	->setMethod('DELETE')
    	->add('submit', 'submit', array(
    			'label' => 'Delete',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	))
    	->getForm()
    	;
    }
}
