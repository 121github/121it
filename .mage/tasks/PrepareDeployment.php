<?php

namespace Task;

use Mage\Task\AbstractTask;

class PrepareDeployment extends AbstractTask
{
    public function getName()
    {
        return 'Preparing the deployment on the production environment';
    }

    public function run()
    {
        $commandList = array(
            'mv app/config/parameters.yml.dist app/config/parameters.yml',
            'rm -rf app/config/parameters.yml.*',
            'rm -rf web/app.php.*'
        );

        $command = implode(" && ", $commandList);

        $result = $this->runCommandRemote($command);

        return $result;
    }
}