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
            'rm -rf web/app.php.*',
            'setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs',
            'setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs'
        );

        $command = implode(" && ", $commandList);

        $result = $this->runCommandRemote($command);

        return $result;
    }
}