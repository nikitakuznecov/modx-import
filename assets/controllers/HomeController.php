<?php
namespace Import\Assets\Controllers;

use Import\Base\ParseCSV;
use Import\Helpers\Messages;
use Import\Helpers\Cookie;

class HomeController extends CmsController
{
    protected $parser;

    public function index(){}
    
    public function getImport()
    {
      $this->parser = $this->di->get('parser');
      $config = $this->di->get('config');
      $modx = $this->di->get("modx");

      $category_template = $config['ImportConfig']['main_cat_template'];
      $product_template = $config['ImportConfig']['main_prod_template'];
      $generalId = $config['ImportConfig']['main_general_categoryID'];

      $this->parser->parse();
      
      $_SESSION['allCat']['items'] = $this->parser->getCategoriesArray();
      $_SESSION['allProd']['items'] = $this->parser->getProductsArray();

      $_SESSION['newCat']['items'] =   array_filter($_SESSION['allCat']['items'], function($val) {return ($val == true);}, ARRAY_FILTER_USE_KEY);
      $_SESSION['updCat']['items'] =   array_filter($_SESSION['allCat']['items'], function($val) {return ($val == false);}, ARRAY_FILTER_USE_KEY);
      $_SESSION['newProd']['items'] =  array_filter($_SESSION['allProd']['items'], function($val) {return ($val == true);}, ARRAY_FILTER_USE_KEY);
      $_SESSION['updProd']['items'] =  array_filter($_SESSION['allProd']['items'], function($val) {return ($val == false);}, ARRAY_FILTER_USE_KEY);

      $_SESSION['newProd']['amount'] = sizeof($_SESSION['newProd']['items']);
      $_SESSION['updProd']['amount'] = sizeof($_SESSION['updProd']['items']);
      $_SESSION['newCat']['amount'] =  sizeof($_SESSION['newCat']['items']);
      $_SESSION['updCat']['amount'] =  sizeof($_SESSION['updCat']['items']);

      $_SESSION['newProd']['start'] = $_SESSION['updProd']['start'] = $_SESSION['newCat']['start'] = $_SESSION['updCat']['start'] = 0;
      $_SESSION['newProd']['limit'] = $_SESSION['updProd']['limit'] = $config['ImportConfig']['main_step'];
      $_SESSION['newCat']['limit'] = $_SESSION['updCat']['limit'] = $config['ImportConfig']['main_step'];

      Messages::messager(array("products" => $_SESSION['allProd']['items'],"categories" => $_SESSION['allCat']['items'],"updCat" => $_SESSION['updCat']['items']));

    }
    
    public function categoriesUpdate()
    {
      $modx = $this->di->get("modx");
      $config = $this->di->get("config");
      $category_template = $config['ImportConfig']['main_cat_template'];
      $idParrent = $config['ImportConfig']['main_general_categoryID'];

      if (!empty($_SESSION['updCat']['items'])) {

          $amount = $_SESSION['updCat']['amount'];
          $start = $_SESSION['updCat']['start'];
          $limit = $_SESSION['updCat']['start'] + $_SESSION['updCat']['limit'];

          if ($limit >= $amount) {$limit = $amount;$status = 'stop';} else {$status = 'next';}  

          for ($i=$start; $i < $limit; $i++) { 
            

          }
          $_SESSION['updCat']['start'] = $i;

          if($status == 'next'){

            Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Обновление категорий','dataType' => 'categoriesUpdate'));
            
          }else{

            Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Обновление категорий','dataType' => 'categoriesCreate'));
          }
          

      }else{

          Messages::messager(array('amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Обновление категорий','dataType' => 'categoriesCreate'),true);

      }
    }
    public function categoriesCreate()
    {
      $modx = $this->di->get("modx");
      $config = $this->di->get("config");
      $category_template = $config['ImportConfig']['main_cat_template'];
      $idParrent = $config['ImportConfig']['main_general_categoryID'];

      if (!empty($_SESSION['newCat']['items'])) {

          $amount = $_SESSION['newCat']['amount'];
          $start = $_SESSION['newCat']['start'];
          $limit = $_SESSION['newCat']['start'] + $_SESSION['newCat']['limit'];

          if ($limit >= $amount) {$limit = $amount;$status = 'stop';} else {$status = 'next';}  

          for ($i=$start; $i < $limit; $i++) { 
            

          }
          $_SESSION['newCat']['start'] = $i;

          if($status == 'next'){

            Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Добавление категорий','dataType' => 'categoriesCreate'));

          }else{

            Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Добавление категорий','dataType' => 'productsUpdate'));

         }
      }else{

          Messages::messager(array('amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Добавление категорий','dataType' => 'productsUpdate'),true);

      }
    }
    public function productsUpdate()
    {
      $modx = $this->di->get("modx");
      $config = $this->di->get("config");
      $category_template = $config['ImportConfig']['main_cat_template'];
      $idParrent = $config['ImportConfig']['main_general_categoryID'];

      if (!empty($_SESSION['updProd']['items'])) {

          $amount = $_SESSION['updProd']['amount'];
          $start = $_SESSION['updProd']['start'];
          $limit = $_SESSION['updProd']['start'] + $_SESSION['updProd']['limit'];

          if ($limit >= $amount) {$limit = $amount;$status = 'stop';} else {$status = 'next';}  

          for ($i=$start; $i < $limit; $i++) { 
            

          }
          $_SESSION['updProd']['start'] = $i;

        if($status == 'next'){

          Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Обновление товаров','dataType' => 'productsUpdate'));

        }else{

          Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Обновление товаров','dataType' => 'productsCreate'));

        }
      }else{

          Messages::messager(array('amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Обновление товаров','dataType' => 'productsCreate'),true);

      }
    }
    public function productsCreate()
    {
      $modx = $this->di->get("modx");
      $config = $this->di->get("config");
      $category_template = $config['ImportConfig']['main_cat_template'];
      $idParrent = $config['ImportConfig']['main_general_categoryID'];

      if (!empty($_SESSION['newProd']['items'])) {

          $amount = $_SESSION['newProd']['amount'];
          $start = $_SESSION['newProd']['start'];
          $limit = $_SESSION['newProd']['start'] + $_SESSION['newProd']['limit'];

          if ($limit >= $amount) {$limit = $amount;$status = 'stop';} else {$status = 'next';}  

          for ($i=$start; $i < $limit; $i++) { 
            

          }
          $_SESSION['newProd']['start'] = $i;

          if($status == 'next'){

            Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Добавление товаров','dataType' => 'productsCreate'));

          }else{

            Messages::messager(array('amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Добавление товаров','dataType' => 'finished'));

          }
      }else{

          Messages::messager(array('amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Добавление товаров','dataType' => 'finished'),true);

      }
    }
    public function finished()
    {
        Messages::messager(array('amount' => $_SESSION['newProd']['amount'],'uploaded' => $_SESSION['newProd']['amount'],'status' => 'stop','caption' => 'Выгрузка окончена','dataType' => 'done'));

        unset($_SESSION['newCat'], $_SESSION['newProd'], $_SESSION['updProd'], $_SESSION['updCat']);

    }
    


}
?>