<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new It121\DashboardBundle\DashboardBundle(),
            new It121\BackendBundle\BackendBundle(),
            new JQuery\JQueryBundle\JqueryBundle(),
        	new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new It121\UserBundle\UserBundle(),
            new It121\ServerBundle\ServerBundle(),
            new It121\ProjectBundle\ProjectBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new It121\LogBundle\LogBundle(),
            new It121\CallSysBundle\CallSysBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new BoxUk\PostcodesIoBundle\BoxUkPostcodesIoBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new It121\AddressBundle\AddressBundle(),
            new Ddeboer\DataImportBundle\DdeboerDataImportBundle(),
            new It121\CalldevBundle\CalldevBundle(),
            new It121\CronjobsBundle\CronjobsBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
