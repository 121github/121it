<?php

namespace It121\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use It121\ProjectBundle\Entity\Project;
use It121\ServerBundle\Entity\Server;
use It121\BackendBundle\Util\Util;
use It121\ProjectBundle\Form\ProjectType;

class ProjectController extends Controller
{
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  PROJECT ACTION *****************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * Project List view
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ProjectBundle:Project')->findAll();

        return $this->render('BackendBundle:Project:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW PROJECT ACTION *************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Finds and displays a Project entity.
     *
     */
    public function showAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:Project')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Project entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:Project:show.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** CREATE PROJECT ACTION ***********************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    
    /**
     * Creates a new Project entity.
     *
     */
    public function createAction(Request $request)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	$project = new Project();
    	$form = $this->createCreateForm($project);
    	$form->handleRequest($request);
    
    	if ($form->isValid()) {
    		$eManager = $this->getDoctrine()->getManager();
    		 
    		Util::setCreateAuditFields($project, $userLogged->getId());
    		 
    		$eManager->persist($project);
    		$eManager->flush();
    
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Project Created!',
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
    
    	return $this->render('BackendBundle:Project:new.html.twig', array(
    			'entity' => $project,
    			'form'   => $form->createView(),
    	));
    }
    
    /**
     * Creates a form to create a Project entity.
     *
     * @param User $project The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Project $project)
    {
    	$form = $this->createForm(new ProjectType(), $project, array(
    			'action' => $this->generateUrl('project_create'),
    			'method' => 'POST',
    	));
    
    	$form->add('submit', 'submit', array(
    			'label' => 'Create',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	));
    
    	return $form;
    }
    
    /**
     * Displays a form to create a new Project entity.
     *
     */
    public function newAction()
    {
    	$project = new Project();
    	$form   = $this->createCreateForm($project);
    
    	return $this->render('BackendBundle:Project:new.html.twig', array(
    			'entity' => $project,
    			'form'   => $form->createView(),
    	));
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** EDIT PROJECT ACTION *************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to edit an existing Project entity.
     *
     */
    public function editAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:Project')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Project entity.');
    	}
    
    	$editForm = $this->createEditForm($entity);
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:Project:edit.html.twig', array(
    			'entity'      => $entity,
    			'edit_form'   => $editForm->createView(),
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Creates a form to edit a Project entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Project $entity)
    {
    	$form = $this->createForm(new ProjectType(), $entity, array(
    			'action' => $this->generateUrl('project_update', array('id' => $entity->getId())),
    			'method' => 'PUT',
    	));
    
    	$form->add('submit', 'submit', array('label' => 'Update'));
    
    	return $form;
    }
    /**
     * Edits an existing Project entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    	 
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	 
    	$eManager = $this->getDoctrine()->getManager();
    
    	$project = $eManager->getRepository('ProjectBundle:Project')->find($id);
    
    	if (!$project) {
    		throw $this->createNotFoundException('Unable to find Project entity.');
    	}
    
    	$editForm = $this->createEditForm($project);
    
    	$editForm->handleRequest($request);
    
    	$editForm->submit($request);
    	if ($editForm->isValid()) {
    		Util::setModifyAuditFields($project, $userLogged->getId());
    		 
    		$eManager->persist($project);
    		$eManager->flush();
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Project Changed!',
    						'message' => 'The project '.$project->getName().' has been updated'
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
    		return $this->render('BackendBundle:Project:edit.html.twig', array(
    				'entity'      => $project,
    				'edit_form'   => $editForm->createView(),
    		));
    	}
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** DELETE PROJECT ******************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to remove an existing Project entity.
     *
     */
    public function removeAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('ProjectBundle:Project')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find Project entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:Project:remove.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Deletes a Project entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$eManager = $this->getDoctrine()->getManager();
    	$project = $eManager->getRepository('ProjectBundle:Project')->find($id);
    	 
    	if (!$project) {
    		throw $this->createNotFoundException('Unable to find Project entity.');
    	}
    	 
    	$form = $this->createDeleteForm($id);
    	$form->handleRequest($request);
    
    
    	if ($form->isValid()) {
    		 
    		$eManager->remove($project);
    		$eManager->flush();
    
    
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Project Deleted!',
    						'message' => 'The project '.$project->getName().' has been deleted'
    				)
    		);
    	}
    	else {
    		 
    		$this->get('session')->getFlashBag()->set(
    				'error',
    				array(
    						'alert' => 'error',
    						'title' => 'Error!',
    						'message' => 'The project '.$project->getName().' has NOT been deleted'
    				)
    		);
    	}
    
    	$result = array();
    	$response = new Response(json_encode($result));
    	$response->headers->set('Content-Type', 'application/json');
    	 
    	return $response;
    }
    
    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param mixed $projectId The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
    	return $this->createFormBuilder()
    	->setAction($this->generateUrl('project_delete', array('id' => $id)))
    	->setMethod('DELETE')
    	->add('submit', 'submit', array(
    			'label' => 'Delete',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	))
    	->getForm()
    	;
    }
}
