<?php

namespace Import\Model\Request;

use Import\Model\absProvider;
use Import\Base\Request;

class Provider extends absProvider
{

    /**
     * @var string
     */
    public $serviceName = 'request';

    /**
     * @return mixed
     */
    public function init()
    {
        $request = new Request();

        $this->di->set($this->serviceName, $request);
    }
}
?>