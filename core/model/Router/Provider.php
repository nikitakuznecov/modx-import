<?php

namespace Import\Model\Router;

use Import\Model\absProvider;
use Import\Base\Router;

class Provider extends absProvider
{

    /**
     * @var string
     */
    public $serviceName = 'router';

    /**
     * @return mixed
     */
    public function init()
    {
        $router = new Router('http://import.ru/import');

        $this->di->set($this->serviceName, $router);
    }
}
?>