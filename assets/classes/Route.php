<?
/**
 * List routes
 */

$this->router->add('home', '/import/', 'HomeController:index');
$this->router->add('getImport', '/import/getImport', 'HomeController:getImport');
$this->router->add('categoriesUpdate', '/import/categoriesUpdate', 'HomeController:categoriesUpdate');
$this->router->add('categoriesCreate', '/import/categoriesCreate', 'HomeController:categoriesCreate');
$this->router->add('productsCreate', '/import/productsCreate', 'HomeController:productsCreate');
$this->router->add('productsUpdate', '/import/productsUpdate', 'HomeController:productsUpdate');
$this->router->add('finished', '/import/finished', 'HomeController:finished');

?>