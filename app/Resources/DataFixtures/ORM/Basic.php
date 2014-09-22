 <?php

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use It121\BackendBundle\Entity\User;
use It121\BackendBundle\Entity\UserDetail;
use It121\BackendBundle\Entity\UserRepository;
use It121\BackendBundle\Entity\UserRole;
use It121\BackendBundle\Util\Util;

/**
 * Basic version of the complete fixtures
 * The code that use the ACL and the security component has been deleted
 * 
 * If you want to load the basic version:
 * $ php app/console doctrine:fixtures:load --fixtures=app/Resources
 * 
 *       
 */
class Basico implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    public function load(ObjectManager $manager)
    {
    	
    	/*********************************************************************/
    	/******************* USERS *******************************************/
    	/*********************************************************************/
    	
        // Roles
        foreach (array('ROLE_USER','ROLE_ADMINISTRATOR',) as $name) {
            $role = new UserRole();
            $role->setName($name);
            Util::setCreateAuditFields($role, 1);
            
            $manager->persist($role);
        }

        $manager->flush();
        
        
        //Create one admin user
        $user = new User();
        $user->setLogin('admin');
        $user->setRole($role);
        $user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $passwordDecoded = 'pass123';
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $passwordCoded = $encoder->encodePassword($passwordDecoded, $user->getSalt());
        $user->setPassword($passwordCoded);
        Util::setCreateAuditFields($user, 1);
        $manager->persist($user);
         
        //User Details
        $userDetail = new UserDetail();
        $userDetail->setFirstname('Esteban');
        $userDetail->setLastname('Correa');
        $userDetail->setEmail('estebanc@121customerinsight.co.uk');
        $userDetail->setTelephone('01511234567 ');
        $userDetail->setMobile('07123456789 ');
        $userDetail->setUser($user);
        Util::setCreateAuditFields($userDetail, 1);
        $manager->persist($userDetail);
        
        $manager->flush();
        
        //Create one normal user
        $user = new User();
        $user->setLogin('estebanc');
        $user->setRole($role);
        $user->setSalt(base_convert(sha1(uniqid(mt_rand(), true)), 16, 36));
        $passwordDecoded = 'pass123';
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $passwordCoded = $encoder->encodePassword($passwordDecoded, $user->getSalt());
        $user->setPassword($passwordCoded);
        Util::setCreateAuditFields($user, 1);
        $manager->persist($user);
         
        //User Details
        $userDetail = new UserDetail();
        $userDetail->setFirstname('Esteban');
        $userDetail->setLastname('Correa');
        $userDetail->setEmail('estebanc@121customerinsight.co.uk');
        $userDetail->setTelephone('01511234567 ');
        $userDetail->setMobile('07123456789 ');
        $userDetail->setUser($user);
        Util::setCreateAuditFields($userDetail, 1);
        $manager->persist($userDetail);
        
        $manager->flush();
        
    }
    
}