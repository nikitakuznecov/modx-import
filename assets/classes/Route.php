<?
/**
 * List routes
 */

$this->router->add('home', '/import/', 'HomeController:index');
$this->router->add('getImport', '/import/getImport', 'HomeController:getImport');
$this->router->add('showNewProducts', '/import/showNewProducts', 'HomeController:showNewProducts');
$this->router->add('showUpdatedProducts', '/import/showUpdatedProducts', 'HomeController:showUpdatedProducts');
$this->router->add('showNewCatalog', '/import/showNewCatalog', 'HomeController:showNewCatalog');
$this->router->add('showUpdatedCatalog', '/import/showUpdatedCatalog', 'HomeController:showUpdatedCatalog');
$this->router->add('catalogCreate', '/import/catalogCreate', 'HomeController:catalogCreate');
$this->router->add('catalogUpdate', '/import/catalogUpdate', 'HomeController:catalogUpdate');
$this->router->add('productsCreate', '/import/productsCreate', 'HomeController:productsCreate');
$this->router->add('productsUpdate', '/import/productsUpdate', 'HomeController:productsUpdate');
$this->router->add('finished', '/import/finished', 'HomeController:finished');

?>