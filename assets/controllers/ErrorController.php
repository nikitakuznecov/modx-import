<?php

namespace Import\Assets\Controllers;
use Import\Helpers\Messages;

class ErrorController extends CmsController
{
    public function page404()
    {

        echo Messages::messager('Ошибка 404! Страница не найдена', 'warning');

    }
}
?>