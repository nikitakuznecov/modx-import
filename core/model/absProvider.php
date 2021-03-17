<?php

namespace Import\Model;
use Import\Base\Di;

abstract class absProvider
{
    /**
     * @var \DI;
     */
    protected $di;

    /**
     * AbstractProvider constructor.
     * @param ImportModel\DI $di
     */
    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * @return mixed
     */
    abstract function init();
}