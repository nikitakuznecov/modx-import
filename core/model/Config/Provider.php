<?php

namespace Import\Model\Config;

use Import\Model\absProvider;
use Import\Base\Config;

class Provider extends absProvider
{

    /**
     * @var string
     */
    public $serviceName = 'config';

    /**
     * @return mixed
     */
    public function init()
    {
        $config['ImportConfig'] = Config::file('main.php');
        //$config['database'] = Config::file('database.php'); 

        $this->di->set($this->serviceName, $config);
    }
}