 <?php

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use It121\UserBundle\Entity\User;
use It121\UserBundle\Entity\UserDetail;
use It121\UserBundle\Entity\UserRepository;
use It121\UserBundle\Entity\UserRole;
use It121\BackendBundle\Util\Util;
use It121\ServerBundle\Entity\ServerType;
use It121\ServerBundle\Entity\ServerSubtype;
use It121\ServerBundle\Entity\Server;
use It121\ServerBundle\Entity\ServerStatus;
use It121\ProjectBundle\Entity\Project;

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
        $user->setRole($manager->getRepository('UserBundle:UserRole')->findOneBy(array('name' => 'ROLE_ADMINISTRATOR')));
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
        $user->setRole($manager->getRepository('UserBundle:UserRole')->findOneBy(array('name' => 'ROLE_USER')));
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

        
        
        /*********************************************************************/
        /******************* SERVERS *****************************************/
        /*********************************************************************/
         
        // Server Status
        foreach (array('Ok','Error', 'Warning' ) as $name) {
        	$status = new ServerStatus();
        	$status->setName($name);
        	Util::setCreateAuditFields($status, 1);
        	$manager->persist($status);
        }
        
        // Server Type
        foreach (array('Service','Server', 'Website',) as $name) {
        	$type = new ServerType();
        	$type->setName($name);
        	Util::setCreateAuditFields($type, 1);
        
        	$manager->persist($type);
        }
        
        $manager->flush();
        
        // Server Subtype for Service
        foreach (array('Email','FTP', ) as $name) {
        	$subtype = new ServerSubtype();
        	$subtype->setName($name);
        	$subtype->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Service')));
        	Util::setCreateAuditFields($subtype, 1);
        	$manager->persist($subtype);
        }
        
        // Server Subtype for Website
        foreach (array('Developement','Test', 'Preproduction', 'Production', 'Management' ) as $name) {
        	$subtype = new ServerSubtype();
        	$subtype->setName($name);
        	$subtype->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        	Util::setCreateAuditFields($subtype, 1);
        	$manager->persist($subtype);
        }
        
        $manager->flush();
        
        
        //Create service (FTP)
        $server = new Server();
        $server->setName('test.leadcontrol');
        $server->setDomain('test.leadcontrol.co.uk');
        $server->setUser('devtest');
        $server->setPassword('dev123');
        $server->setPort(21);
        $server->setSendEmail(false);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Service')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'FTP')));
        $server->setLastOnline(new DateTime('now - 2 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.234556);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        
        //Create service (Email)
        $server = new Server();
        $server->setName('SMTP test.leadcontrol');
        $server->setDomain('mail.leadcontrol.co.uk');
        $server->setUser('admin@leadcontrol.co.uk');
        $server->setPassword('IAfMV.M;mwX2');
        $server->setSendEmail(false);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Service')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Email')));
        $server->setLastOnline(new DateTime('now - 12 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.014556);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        
        //Create website (Test)
        $server = new Server();
        $server->setName('Voice Group Test');
        $server->setDomain('http://121webhost/voicegroup/web/');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Test')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('Voice Group Test');
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        
        
        //Create website (Management)
        $server = new Server();
        $server->setName('Phabricator');
        $server->setDomain('http://www.121leads.co.uk/');
        $server->setPort(9090);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Warning')));
        $server->setLatency(0.12356);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('Phabricator');
        $project->setStartDate(new DateTime('2014-09-10 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-10 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        
        //Create website (Management)
        $server = new Server();
        $server->setName('Jenkins');
        $server->setDomain('http://www.121leads.co.uk/');
        $server->setPort(8080);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('Phabricator');
        $project->setStartDate(new DateTime('2014-09-10 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-10 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        
        $manager->flush();
        
    }
    
}