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

      $_SESSION['newCat']['items'] =   $this->filter_array($_SESSION['allCat']['items'],'state',false);
      $_SESSION['updCat']['items'] =   $this->filter_array($_SESSION['allCat']['items'],'state',true);
      $_SESSION['newProd']['items'] =  $this->filter_array($_SESSION['allProd']['items'],'state',false); 
      $_SESSION['updProd']['items'] =  $this->filter_array($_SESSION['allProd']['items'],'state',true); 

      $_SESSION['newProd']['amount'] = sizeof($_SESSION['newProd']['items']);
      $_SESSION['updProd']['amount'] = sizeof($_SESSION['updProd']['items']);
      $_SESSION['newCat']['amount'] =  sizeof($_SESSION['newCat']['items']);
      $_SESSION['updCat']['amount'] =  sizeof($_SESSION['updCat']['items']);

      $_SESSION['newProd']['start'] = $_SESSION['updProd']['start']= $_SESSION['newProd']['start'] = $_SESSION['newCat']['start'] = $_SESSION['updCat']['start'] = 0;
      $_SESSION['newProd']['limit'] = $_SESSION['updProd']['limit'] = $config['ImportConfig']['main_step'];
      $_SESSION['newCat']['limit'] = $_SESSION['updCat']['limit'] = $config['ImportConfig']['main_step'];

      Messages::messager(array("products" => $_SESSION['allProd']['items'],"categories" => $_SESSION['allCat']['items'],"information" =>$_SESSION['newCat']['items'])); 

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
            
            //Пытаемся найти категорию, если получается идем дальше
            if($id = $modx->getObject('modResource',array('link_attributes'=> $_SESSION['updCat']['items'][$i]['pagetitle']))->get('id')){

              //Массив параметров для импорта по умолчанию 
              $arrayDef = array('id'=>$id,'published' => 1,'context_key' => 'web');

              //Формируем одну строку со всеми указанными параметрами в массив
              $importArray = $this->importArray( $_SESSION['updCat']['items'], $i ,$arrayDef);

              //Запускаем процессор и передаем ему массив параметров 
              $response = $modx->runProcessor('resource/update', $importArray);

              //Если что-то не так нам процессор вернет ошибку и мы отдаем на обработку js
              if ($response->response['success'] == false) {

                foreach ($response->errors as $key => $value) {

                    $modx->error->reset();

                }
              }
            }
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

          if ($limit >= $amount) {$limit = $amount;$status = 'stop';} else {$status = 'next';}   // тут ошибка в лимите - он идет не до конца цикла

          for ($i=$start; $i < $limit; $i++) { 
         
            //Проверим есть ли значение с таким ключем в исходном массиве, если есть то идем дальше
            if(!empty($_SESSION['newCat']['items'][$i]['pagetitle'])){
              //Массив параметров для импорта по умолчанию 
              $arrayDef = array('link_attributes'=> $_SESSION['newCat']['items'][$i]['pagetitle'],'parent' => $idParrent,'template' => $category_template,'isfolder' => 1,'published' => 1,'class_key' => 'msCategory','context_key' => 'web');

              //Формируем одну строку со всеми указанными параметрами в массив
              $importArray = $this->importArray( $_SESSION['newCat']['items'], $i ,$arrayDef);

              //Запускаем процессор и передаем ему массив параметров 
              $response = $modx->runProcessor('resource/create', $importArray);

              //Если что-то не так нам процессор вернет ошибку и мы отдаем на обработку js
              if ($response->response['success'] == false) {

                foreach ($response->errors as $key => $value) {

                    $modx->error->reset();

                }
              }
            }

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
    
    function importArray( $array, $iterator = 0, $mixed)
    {
    
            $result = array();
            
            $k = array_keys($array[$iterator]);
            
            $v = array_values($array[$iterator]);
            
            foreach ($k as $key => $value) {
    
               if($value !== 'state'){

                   $result[$value] = $v[$key];

               }
    
            }
            
            return  array_merge ($result, $mixed);
    
    }
    public function filter_array($array,$the_rule,$condition){
         
      $result = array();

      if($array && $the_rule && $condition !== null){
        
        foreach($array as $key => $value){
          
          if($array[$key][$the_rule] === $condition){

            $result[$key] = $array[$key];

          }
        }
      }

      return $result;
    }

}
?>