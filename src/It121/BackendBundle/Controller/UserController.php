<?php

namespace It121\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use It121\UserBundle\Entity\User;
use It121\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use It121\UserBundle\Form\UserEditType;
use Symfony\Component\Form\FormError;
use It121\BackendBundle\Util\Util;
use It121\UserBundle\Entity\UserChangePassword;
use It121\UserBundle\Form\UserChangePasswordType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
	
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************** LOGIN ACTION ********************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
	/**
	 * Login action
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function loginAction(){
			
		$request = $this->getRequest();
		$session = $request->getSession();
			
		$error = $request->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR,
				$session->get(SecurityContext::AUTHENTICATION_ERROR)
		);
	
		return $this->render('BackendBundle:User:login.html.twig', array(
				'last_username' => $session->get(SecurityContext::LAST_USERNAME),
				'error' => $error
		));
	}

	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/********************************  USER ACTION ********************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	/******************************************************************************************************************************/
	
    /**
     * User List view
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('BackendBundle:User:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** CREATE USER ACTION **************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    
    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    	 
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	
        $user = new User();
        $form = $this->createCreateForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
        	$eManager = $this->getDoctrine()->getManager();
        	
        	$encoder = $this->get('security.encoder_factory')->getEncoder($user);
        	
        	$user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        	
        	$passwordEncoded = $encoder->encodePassword(
        			$user->getPassword(),
        			$user->getSalt()
        	);
        	
        	$user->setPassword($passwordEncoded);
        	
        	Util::setCreateAuditFields($user, $userLogged->getId());
        	
            //Save the EmpDetail
            $userDetail = $user->getUserDetail();
            $userDetail->setUser($user);
            
            Util::setCreateAuditFields($userDetail, $userLogged->getId());
            
            
            $eManager->persist($user);
            $eManager->persist($userDetail);
            $eManager->flush();
            
            
            $this->get('session')->getFlashBag()->set(
            		'success',
            		array(
            				'alert' => 'success',
            				'title' => 'User Created!',
            				'message' => 'The user has been created'
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

        return $this->render('BackendBundle:User:new.html.twig', array(
            'entity' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a User entity.
    *
    * @param User $user The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array(
        		'label' => 'Create',
        		'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
        ));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $user = new User();
        $form   = $this->createCreateForm($user);

        return $this->render('BackendBundle:User:new.html.twig', array(
            'entity' => $user,
            'form'   => $form->createView(),
        ));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** SHOW USER ACTION ****************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** EDIT USER ACTION ****************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new UserEditType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
    $userLogged = $this->get('security.context')->getToken()->getUser();
    	
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	
    	
        $eManager = $this->getDoctrine()->getManager();

        $user = $eManager->getRepository('UserBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($user);
        
        $editForm->handleRequest($request);
        
        $originalPassword = $editForm->getData()->getPassword();
        
        $editForm->submit($request);
        if ($editForm->isValid()) {
 
        	if (null == $user->getPassword()) {
        		//No changes in the password
        		$user->setPassword($originalPassword);
        	}
        	else {
        		$encoder = $this->get('security.encoder_factory')->getEncoder($user);
        		 
        		$passwordEncoded = $encoder->encodePassword(
        				$user->getPassword(),
        				$user->getSalt()
        		);
        		$user->setPassword($passwordEncoded);
        	}
        	
        	Util::setModifyAuditFields($user, $userLogged->getId());
        	
        	$userDetail = $user->getUserDetail();
        	Util::setModifyAuditFields($userDetail, $userLogged->getId());
        	$userDetail->setUser($user);
        	
        	
        	$eManager->persist($user);
        	$eManager->persist($userDetail);
            $eManager->flush();
            
            $this->get('session')->getFlashBag()->set(
            		'success',
            		array(
            				'alert' => 'success',
            				'title' => 'User Changed!',
            				'message' => 'The user '.$user->getLogin().' has been updated'
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
        	return $this->render('BackendBundle:User:edit.html.twig', array(
	            'entity'      => $user,
	            'edit_form'   => $editForm->createView(),
	        ));
        }
    }
    
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** CHANGE PASSWORD ACTION **********************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Change password
     *
     */
    public function changePasswordAction(Request $request, $id)
    {
    	$userLogged = $this->get('security.context')->getToken()->getUser();
    	 
    	if (!$userLogged) {
    		throw $this->createNotFoundException('Unable to find this user.');
    	}
    	 
    	$eManager = $this->getDoctrine()->getManager();
    
    	$user = $eManager->getRepository('UserBundle:User')->find($id);
    
    	if (!$user) {
    		throw $this->createNotFoundException('Unable to find User entity.');
    	}
    
    	$userChangePassword = new UserChangePassword();
    	$passwordForm = $this->createChangePasswordForm($userChangePassword, $user->getId());
    	$passwordForm->handleRequest($request);
    	 
    	$encoder = $this->get('security.encoder_factory')->getEncoder($user);
    	 
    	//Check the oldPassword
    	if ($userChangePassword->getOldPassword()) {
    		$isValid = $encoder->isPasswordValid($user->getPassword(), $userChangePassword->getOldPassword(), $user->getSalt());
    
    		if (!$isValid) {
    			$passwordForm->addError(new FormError("The oldPassword is not correct"));
    		}
    	}
    	 
    	if ($passwordForm->isValid()) {
    
    		$passwordEncoded = $encoder->encodePassword(
    				$userChangePassword->getPassword(),
    				$user->getSalt()
    		);
    		$user->setPassword($passwordEncoded);
    		 
    		Util::setModifyAuditFields($user, $userLogged->getId());
    		 
    		$eManager->persist($user);
    		$eManager->flush();
    		
    		$this->get('session')->getFlashBag()->set(
    				'success',
    				array(
    						'alert' => 'success',
    						'title' => 'Password Changed!',
    						'message' => 'The user password has been updated'
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
    
    	return $this->render('BackendBundle:User:password.html.twig', array(
    			'entity'      => $user,
    			'password_form'   => $passwordForm->createView(),
    	));
    }
    
    /**
     * Creates a form to change the password of a User entity by id.
     *
     * @param $user the user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createChangePasswordForm(UserChangePassword $userChangePassword, $id)
    {
    	$form = $this->createForm(new UserChangePasswordType(), $userChangePassword, array(
    			'action' => $this->generateUrl('user_password', array('id' => $id)),
    			'method' => 'POST',
    	));
    
    	$form->add('submit', 'submit', array(
    			'label' => 'Change',
    			'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
    	));
    
    	return $form;
    }
    
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************** DELETE ACTION *******************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    /******************************************************************************************************************************/
    
    /**
     * Displays a form to remove an existing User entity.
     *
     */
    public function removeAction($id)
    {
    	$em = $this->getDoctrine()->getManager();
    
    	$entity = $em->getRepository('UserBundle:User')->find($id);
    
    	if (!$entity) {
    		throw $this->createNotFoundException('Unable to find User entity.');
    	}
    
    	$deleteForm = $this->createDeleteForm($id);
    
    	return $this->render('BackendBundle:User:remove.html.twig', array(
    			'entity'      => $entity,
    			'delete_form' => $deleteForm->createView(),
    	));
    }
    
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
    	$eManager = $this->getDoctrine()->getManager();
    	$user = $eManager->getRepository('UserBundle:User')->find($id);
    	
    	if (!$user) {
    		throw $this->createNotFoundException('Unable to find User entity.');
    	}
    	
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
        

        if ($form->isValid()) {
        	
        	$eManager->remove($user->getUserDetail());
            $eManager->remove($user);
            $eManager->flush();
            
            
            $this->get('session')->getFlashBag()->set(
            		'success',
            		array(
            				'alert' => 'success',
            				'title' => 'User Deleted!',
            				'message' => 'The user '.$user->getUserDetail().' has been deleted'
            		)
            );
        }
        else {
        	
        	$this->get('session')->getFlashBag()->set(
        			'error',
        			array(
        					'alert' => 'error',
        					'title' => 'Error!',
        					'message' => 'The user '.$user->getUserDetail().' has NOT been deleted'
        			)
        	);
        }
        
        $result = array();
        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
         
        return $response;
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $userId The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array(
            		'label' => 'Delete',
            		'attr' => array('class' => 'ui-btn ui-corner-all ui-shadow ui-btn-b ui-btn-icon-left ui-icon-check')
            ))
            ->getForm()
        ;
    }
}
