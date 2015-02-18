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
use It121\ServerBundle\Entity\ServerEnvironment;
use It121\ServerBundle\Entity\Server;
use It121\ServerBundle\Entity\ServerStatus;
use It121\ProjectBundle\Entity\Project;
use It121\ProjectBundle\Entity\ProjectGroup;
use It121\ProjectBundle\Entity\ProjectStatus;

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
        
        // Project Status
        foreach (array('Ok','Error', 'Warning', 'In Progress' ) as $name) {
        	$projectStatus = new ProjectStatus();
        	$projectStatus->setName($name);
        	Util::setCreateAuditFields($projectStatus, 1);
        	$manager->persist($projectStatus);
        }
        
        // Server Type
        foreach (array('Service', 'Website',) as $name) {
        	$type = new ServerType();
        	$type->setName($name);
        	Util::setCreateAuditFields($type, 1);
        
        	$manager->persist($type);
        }
        
        $manager->flush();
        
        
        // Server Subtype for Service
        foreach (array('Email','FTP', 'SFTP', ) as $name) {
        	$subtype = new ServerSubtype();
        	$subtype->setName($name);
        	$subtype->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Service')));
        	Util::setCreateAuditFields($subtype, 1);
        	$manager->persist($subtype);
        }
        
        // Server Subtype for Website
        foreach (array('Development', 'Management' ) as $name) {
        	$subtype = new ServerSubtype();
        	$subtype->setName($name);
        	$subtype->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        	Util::setCreateAuditFields($subtype, 1);
        	$manager->persist($subtype);
        }
        
        $manager->flush();
        
        
        // Server Environments
        foreach (array('Development','Test', 'Acceptance', 'Production') as $name) {
        	$environment = new ServerEnvironment();
        	$environment->setName($name);
        	Util::setCreateAuditFields($environment, 1);
        	$manager->persist($environment);
        }
        
        $manager->flush();
        
        //Create service (FTP)
        $server = new Server();
        $server->setName('FTP test.leadcontrol');
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
        $server->setShortcut(false);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        $manager->flush();
        
        //Create service (SFTP)
        $server = new Server();
        $server->setName('SFTP 121webhost');
        $server->setDomain('10.10.1.13');
        $server->setUser('esteban');
        $server->setPassword('pass123');
        $server->setPort(22);
        $server->setSendEmail(false);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Service')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'SFTP')));
        $server->setLastOnline(new DateTime('now - 2 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.234556);
        $server->setShortcut(false);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        $manager->flush();
        
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
        $server->setShortcut(false);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        $manager->flush();
        
        //Create website (Test)
        $server = new Server();
        $server->setName('CMS Test');
        $server->setDomain('10.10.1.13');
        $server->setPath('test_env/cms/web');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Test')));
        $server->setRssUrl('http://10.10.1.13:8080/view/01_Test/job/Cms_test_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("CMS");
        $projectGroup->setRssUrl('http://10.10.1.13:8080/job/CMS/rssAll');
        $projectGroup->setDescription("Calendar Management System");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('CMS Test');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Ok')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Production)
        $server = new Server();
        $server->setName('Fsb CMS');
        $server->setDomain('10.10.1.13');
        $server->setPath('fsb/web');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Production')));
        $server->setRssUrl('http://10.10.1.13:8080/view/03_Prod/job/Fsb_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('fsb.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('Fsb CMS');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Ok')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Production)
        $server = new Server();
        $server->setName('Voice Group CMS');
        $server->setDomain('10.10.1.13');
        $server->setPath('voicegroup/web');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Production')));
        $server->setRssUrl('http://10.10.1.13:8080/view/03_Prod/job/Voicegroup_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('voice_group.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('Voice Group CMS');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Ok')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Test)
        $server = new Server();
        $server->setName('121IT Test');
        $server->setDomain('10.10.1.15');
        $server->setPath('121it/web');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Test')));
        $server->setRssUrl('http://10.10.1.13:8080/view/01_Test/job/121It_test_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        $server->setLogo('121.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("121IT");
        $projectGroup->setRssUrl('http://www.10.10.1.13:8080/job/121IT/rssAll');
        $projectGroup->setDescription("IT Management System");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('121IT Test');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Ok')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();

        //Create website (Acceptance)
        $server = new Server();
        $server->setName('121IT Acceptance');
        $server->setDomain('10.10.1.13');
        $server->setPath('accept_env/121it');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Acceptance')));
        $server->setRssUrl('http://10.10.1.13:8080/view/02_Accept/job/121It_accept_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        $server->setLogo('121.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('121IT Acceptance');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Warning')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();

        //Create website (Production)
        $server = new Server();
        $server->setName('121IT Production');
        $server->setDomain('10.10.1.13');
        $server->setPath('121it');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Production')));
        $server->setRssUrl('http://10.10.1.13:8080/view/03_Prod/job/121It_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        $server->setLogo('121.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('121IT Production');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Error')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Test)
        $server = new Server();
        $server->setName('121Sys Test');
        $server->setDomain('10.10.1.13');
        $server->setPath('test_env/121sys');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Test')));
        $server->setRssUrl('http://10.10.1.13:8080/view/01_Test/job/121Sys_test_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        $server->setLogo('121.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("121Sys");
        $projectGroup->setRssUrl('http://www.10.10.1.13:8080/job/121Sys/rssAll');
        $projectGroup->setDescription("Agent Management System");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('121Sys Test');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'In Progress')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Acceptance)
        $server = new Server();
        $server->setName('121Sys Acceptance');
        $server->setDomain('10.10.1.13');
        $server->setPath('accept_env/121sys');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Acceptance')));
        $server->setRssUrl('http://10.10.1.13:8080/view/02_Accept/job/121Sys_accept_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        $server->setLogo('121.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('121Sys Acceptance');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Warning')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Production)
        $server = new Server();
        $server->setName('121 Calling System');
        $server->setDomain('10.10.1.13');
        $server->setPath('121sys');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Development')));
        $server->setEnvironment($manager->getRepository('ServerBundle:ServerEnvironment')->findOneBy(array('name' => 'Production')));
        $server->setRssUrl('http://10.10.1.13:8080/view/03_Prod/job/121Sys_deployment/rssAll');
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Error')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('121.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('121Sys Production');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setLastDeployment(new DateTime('2014-09-18 00:00:00'));
        $project->setStatus($manager->getRepository('ProjectBundle:ProjectStatus')->findOneBy(array('name' => 'Error')));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        
        //Create website (Management)
        $server = new Server();
        $server->setName('Phabricator');
        $server->setDomain('10.10.1.13');
        $server->setPort(9090);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Warning')));
        $server->setLatency(0.12356);
        $server->setShortcut(false);
        $server->setLogo('phabricator.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("Issues");
        $projectGroup->setDescription("Issues Management System");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('Phabricator');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();

        //Create website (Management)
        $server = new Server();
        $server->setName('YouTrack');
        $server->setDomain('10.10.1.13');
        $server->setPort(8081);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Warning')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('youtrack.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('YouTrack');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        $manager->flush();
        
        //Create website (Management)
        $server = new Server();
        $server->setName('Jenkins');
        $server->setDomain('10.10.1.13');
        $server->setPort(8080);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('jenkins.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("Continuous Integration");
        $projectGroup->setDescription("Continuous Integration Management System");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('Jenkins');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        
        $manager->flush();
        
        //Create website (Management)
        $server = new Server();
        $server->setName('Alfresco');
        $server->setDomain('10.10.1.13');
        $server->setPath('share/page');
        $server->setPort(8181);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('alfresco.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("Documents");
        $projectGroup->setDescription("Documental Management System");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('Alfresco');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);
        
        $manager->flush();

        //Create website (Management)
        $server = new Server();
        $server->setName('Spiceworks');
        $server->setDomain('10.10.1.16');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('spiceworks.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $projectGroup = new ProjectGroup();
        $projectGroup->setName("Network");
        $projectGroup->setDescription("Network Monitoring");
        Util::setCreateAuditFields($projectGroup, 1);
        $manager->persist($projectGroup);
        $project = new Project();
        $project->setName('Spiceworks');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);

        $manager->flush();

        //Create website (Management)
        $server = new Server();
        $server->setName('Munin');
        $server->setDomain('10.10.1.13');
        $server->setPath('munin');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('munin.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('Munin');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);

        $manager->flush();

        //Create website (Management)
        $server = new Server();
        $server->setName('DB Prod');
        $server->setDomain('10.10.1.13');
        $server->setPath('onedatamyadmin');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('phpmyadmin.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('DB Prod');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);

        $manager->flush();

        //Create website (Management)
        $server = new Server();
        $server->setName('DB Dev');
        $server->setDomain('10.10.1.15');
        $server->setPath('phpmyadmin');
        $server->setPort(80);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('phpmyadmin.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('DB Dev');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);

        $manager->flush();

        //Create website (Management)
        $server = new Server();
        $server->setName('CPanel');
        $server->setDomain('mail.leadcontrol.co.uk');
        $server->setPath('');
        $server->setPort(2083);
        $server->setSendEmail(true);
        $server->setMonitoring(true);
        $server->setType($manager->getRepository('ServerBundle:ServerType')->findOneBy(array('name' => 'Website')));
        $server->setSubtype($manager->getRepository('ServerBundle:ServerSubtype')->findOneBy(array('name' => 'Management')));
        $server->setLastOnline(new DateTime('now - 8 hours'));
        $server->setLastCheck(new DateTime('now - 25 seconds'));
        $server->setStatus($manager->getRepository('ServerBundle:ServerStatus')->findOneBy(array('name' => 'Ok')));
        $server->setLatency(0.12356);
        $server->setShortcut(true);
        $server->setLogo('cpanel.png');
        Util::setCreateAuditFields($server, 1);
        $manager->persist($server);
        //Create associated project
        $project = new Project();
        $project->setName('CPanel');
        $project->setGroup($projectGroup);
        $project->setStartDate(new DateTime('2014-09-18 00:00:00'));
        $project->setServer($server);
        Util::setCreateAuditFields($project, 1);
        $manager->persist($project);

        $manager->flush();
        
    }
    
}