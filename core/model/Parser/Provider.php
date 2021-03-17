<?php

namespace Import\Model\Parser;

use Import\Model\absProvider;
use Import\Base\ParseCSV;

class Provider extends absProvider
{

    /**
     * @var string
     */
    public $serviceName = 'parser';

    /**
     * @return mixed
     */
    public function init()
    {
        $parser = new ParseCSV($this->di);

        $this->di->set($this->serviceName, $parser);
    }
}
?>