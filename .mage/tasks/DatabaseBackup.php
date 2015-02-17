<?php

namespace Task;

use Mage\Task\AbstractTask;

class DatabaseBackup extends AbstractTask
{
    public function getName()
    {
        return 'Create a database backup';
    }

    public function run()
    {
        $user = $this->getParameter('user');
        $pass = $this->getParameter('pass');
        $host = $this->getParameter('host');
        $database = $this->getParameter('database');
        $path = $this->getParameter('path');

        $releaseId = $this->getReleaseId();

        if ($user && $pass && $host && $database && $path) {
            //$command = 'mysqldump -u cms_fsb -pNhE8qxYnnDtPT7fJ cms_fsb > /var/www/backup/fsb.sql';
            Console::output($releaseId);
            //$result = $this->runCommandRemote($command);
            $result = true;
        }
        else {
            $result = false;
        }

        return $result;
    }
}