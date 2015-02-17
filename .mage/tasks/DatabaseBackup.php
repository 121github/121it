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


        if ($user && $pass && $host && $database) {
            $name = date('YmdHis');
            $path =  'docs/db_bkp';
            $command = 'mysqldump -u '.$user.' -p'.$pass.' '.$database.' > '.$path.'/'.$name.'.sql';
            var_dump($command);
            $result = $this->runCommandRemote($command);
            //$result = false;
        }
        else {
            $result = false;
            var_dump("Error executing the database backup");
        }

        return $result;
    }
}

