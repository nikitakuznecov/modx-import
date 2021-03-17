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
      
      $_SESSION['newCat']['items'] = $this->parser->getCategoriesArray();

      $_SESSION['newProd']['items'] = $this->parser->getProductsArray();

      Messages::messager(array("products" => $_SESSION['newProd']['items'],"categories" => $_SESSION['newCat']['items']));

     /*$_SESSION['updCat']['items'] = $this->array_values($this->parser->get('updCat'));
     $_SESSION['newCat']['items'] = $this->array_values($this->parser->get('newCat'));
     $_SESSION['updProd']['items'] = $this->array_values($this->parser->get('updProd'));
     $_SESSION['newProd']['items'] = $this->array_values($this->parser->get('newProd'));
	 
     unset($_SESSION['newCat']['items']['none']);
     unset($_SESSION['updCat']['items']['none']);
     unset($_SESSION['newProd']['items']['none']);
     unset($_SESSION['updProd']['items']['none']);

      $_SESSION['newProd']['amount'] = sizeof($this->parser->get('newProd'));
      $_SESSION['updProd']['amount'] = sizeof($this->parser->get('updProd'));
      $_SESSION['newCat']['amount'] = sizeof($this->parser->get('newCat'));
      $_SESSION['updCat']['amount'] = sizeof($this->parser->get('updCat'));

      $_SESSION['newProd']['start'] = $_SESSION['updProd']['start'] = $_SESSION['newCat']['start'] = $_SESSION['updCat']['start'] = 0;
      $_SESSION['newProd']['limit'] = $_SESSION['updProd']['limit'] = $config['ImportConfig']['main_step']; //по умолчанию 100
      $_SESSION['newCat']['limit'] = $_SESSION['updCat']['limit'] = $config['ImportConfig']['main_step']; //по умолчанию 30
 
      if (!empty($_SESSION['updCat']['items']) || !empty($_SESSION['newCat']['items']) || !empty($_SESSION['updProd']['items']) || !empty($_SESSION['newProd']['items'])) {

        //проверим включена ли настройка скрытия ресурсов если да скрываем и информируем
        if($config['ImportConfig']['main_import_hide_resources'] !== 0){

            $result = $modx->query("UPDATE `site_content` SET `published` = 0 WHERE `template` = $category_template OR `template` = $product_template NOT `id` <> $generalId "); 

            echo Messages::messager('В файле кофигурации включена настройка скрытия ресурсов, данные были скрыты', 'warning');
        }

         (int) $total = $_SESSION['updCat']['amount'] + $_SESSION['newCat']['amount'] + $_SESSION['updProd']['amount'] + $_SESSION['newProd']['amount'];
         echo Messages::messager('Общее кол-во позиций в выгрузке - '.$total, 'info');
         echo Messages::messager('Категорий для загрузки - '.$_SESSION['newCat']['amount'], 'info');
         echo Messages::messager('Категорий для обновления - '.$_SESSION['updCat']['amount'], 'info');
         echo Messages::messager('Товаров для загрузки - '.$_SESSION['newProd']['amount'], 'info');
         echo Messages::messager('Товаров для обновления - '.$_SESSION['updProd']['amount'], 'info');

        } else {

          echo Messages::messager('Данные не найдены', 'warning');

        }*/

        //Messages::messager('Ошибка',true);
    }

    /*public function catalogCreate()
    {
            //получаем необходимые обьекты
            $modx = $this->di->get("modx");
            $config = $this->di->get("config");
            $category_template = $config['ImportConfig']['main_cat_template'];
            $idParrent = $config['ImportConfig']['main_general_categoryID'];


            //Массив параметров для импорта по умолчанию 
            $arrayDef = array('template' => $category_template,'isfolder' => 1,'published' => 1,'class_key' => 'msCategory','context_key' => 'web');
            
            // Иморт категорий
            if (!empty($_SESSION['newCat']['items'])) {

              $amount = $_SESSION['newCat']['amount'];
              $start = $_SESSION['newCat']['start'];
              $limit = $_SESSION['newCat']['start'] + $_SESSION['newCat']['limit'];

              // Если лимит выше общего количества - значит это последняя итерация
             if ($limit >= $amount) {$limit = $amount;$status = 'stop';} else {$status = 'next';}
          
              // Импорт данных
              for ($i = $start; $i < $limit; $i++) {

                $id = 0;

                $nameCategory = $this->isParrentCheck( $_SESSION['newCat']['items']['pagetitle'][$i] );

                foreach ($nameCategory as $key => $value) {

                    //Подставляем заголовок страницы
                    $_SESSION['newCat']['items']['pagetitle'][$i] =  $value;

                    if($this->isCoincides ($value) != $idParrent){

                        $id = $this->isCoincides ($value); 

                    }else{

                        //пытаемся найти его если его нет тогда возвращем корневой id
                        if($id != 0){$_SESSION['newCat']['items']['parent'][$i] = $id;}

                          //Формируем одну строку со всеми указанными параметрами в массив
                         $importArray = $this->importArray( $_SESSION['newCat']['items'], $i ,$arrayDef); 

                         //Запускаем процессор и передаем ему массив параметров
                         $response = $modx->runProcessor('resource/create', $importArray );

                         $id = $response->response['object']['id']; 
                        
                    }


                }
                       
                 //Если что-то не так нам процессор вернет ошибку и мы отдаем на обработку js
                 if ($response->response['success'] == false) {
                   
                    foreach ($response->errors as $key => $value) {
                       $modx->error->reset();
                     }
                }
              }

              // Увеличиваем старт
              $_SESSION['newCat']['start'] = $i;

              // Отдаём данные в JS
              $result = array('error' => $error,'progress' => ((($i) * 100)) / $amount,'amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Загрузка категорий каталога','dataType' => 'newCat','class' => 'success','message' => 'Новых категорий было загружено');
              echo json_encode($result);

            } else {

              // Отдаём данные в JS
              $result = array('error' => 1,'progress' => 0, 'amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Загрузка категорий каталога','dataType' => 'newCat','class' => 'warning','message' => 'Нет категорий для обновления');
              echo json_encode($result);

            }
    }

    public function catalogUpdate()
    {
            //получаем необходимые обьекты
            $modx = $this->di->get("modx");

            //Обновление категорий
            if (!empty($_SESSION['updCat']['items'])) {

              $amount = $_SESSION['updCat']['amount'];
              $start = $_SESSION['updCat']['start'];
              $limit = $_SESSION['updCat']['start'] + $_SESSION['updCat']['limit'];

              // Если лимит выше общего количества - значит это последняя итерация
             if ($limit >= $amount) 
             {$limit = $amount;$status = 'stop';} 
             else {$status = 'next';}

              // Импорт данных
              for ($i = $start; $i < $limit; $i++) {

                $nameCategory = $this->isParrentCheck( $_SESSION['updCat']['items']['pagetitle'][$i] );

                $_SESSION['updCat']['items']['pagetitle'][$i] = end($nameCategory);

                //Массив параметров для импорта по умолчанию 
                $arrayDef = array('published' => 1,'context_key' => 'web');

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

              // Увеличиваем старт
              $_SESSION['updCat']['start'] = $i;

              // Отдаём данные в JS
              $result = array('error' => $error,'progress' => ((($i) * 100)) / $amount,'amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Обновление категорий каталога','dataType' => 'updCat','class' => 'success','message' => 'Категорий было обновлено');
              echo json_encode($result);

            } else {

              // Отдаём данные в JS
              $result = array('error' => 1,'progress' => 0, 'amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Обновление категорий каталога','dataType' => 'updCat','class' => 'warning','message' => 'Нет категорий для обновления');
              echo json_encode($result);

            }
    }

    public function productsCreate()
    {
            //получаем необходимые обьекты
            $modx = $this->di->get("modx");
            $config = $this->di->get("config");
            $prod_template = $config['ImportConfig']['main_prod_template'];
            $generalId = $config['ImportConfig']['main_prod_template'];
            $relations = $config['ImportConfig']['main_import_relations'];

            // Иморт товаров
            if (!empty($_SESSION['newProd']['items'])) {

              $amount = $_SESSION['newProd']['amount'];
              $start = $_SESSION['newProd']['start'];
              $limit = $_SESSION['newProd']['start'] + $_SESSION['newProd']['limit'];

              // Если лимит выше общего количества - значит это последняя итерация
             if ($limit >= $amount) 
             {$limit = $amount;$status = 'stop';} 
             else {$status = 'next';}

              // Импорт данных
              for ($i = $start; $i < $limit; $i++) {
                  
              //Массив параметров для импорта по умолчанию 
                $arrayDef = array('template' => $prod_template,'isfolder' => 0,'published' => 1,'class_key' => 'msProduct','context_key' => 'web');

                //Формируем одну строку со всеми указанными параметрами в массив
                $importArray = $this->importArray( $_SESSION['newProd']['items'], $i ,$arrayDef);
                
                //Если новые категории не пустые
                if(!empty($_SESSION['newCat']['items'])){

                    //Меняем родителя на существующиего, если его не существует тогда выгружаем по значению конфига
                    $importArrayNewKey = array_replace ( $importArray , array('parent'=>  $this->isCoincides ( end($this->isParrentCheck($_SESSION['newCat']['items']['pagetitle'][$i])))) ); 

                 //Если в новинках пусто идем в обновляемые категории
                }else if(!empty($_SESSION['updCat']['items'])){    

                    //Меняем родителя на существующиего, если его не существует тогда выгружаем по значению конфига
                    $importArrayNewKey = array_replace ( $importArray , array('parent'=> $this->isCoincides ( end($this->isParrentCheck($_SESSION['updCat']['items']['pagetitle'][$i])))) );

                //Берем значение поумолчанию
                }else{

                    //Меняем родителя на существующиего, если его не существует тогда выгружаем по значению конфига
                    $importArrayNewKey =  $importArray; 

                }


                //Запускаем процессор и передаем ему массив параметров 
                $response = $modx->runProcessor('resource/create',  $importArrayNewKey);

                //Если что-то не так нам процессор вернет ошибку и мы отдаем на обработку js
                if ($response->response['success'] == false) {

                  $idProd = $this->isCoincides ($_SESSION['newProd']['items']['pagetitle'][$i]); //id продукта для того чтобы сделать связь
                  
                  $idCat = $importArrayNewKey['parent'];//id категории для того чтобы сделать связь

                 //проверяем включена ли у нас опция на занесение связей дублированных продуктов
                 if($relations !== 0){

                      if($idProd !== $generalId && $idCat !== $generalId){
                          
                          $result = $modx->query("INSERT INTO `ms2_product_categories`(`product_id`, `category_id`) VALUES ($idProd,$idCat)"); 

                      }
                 }

                  foreach ($response->errors as $key => $value) {

                     $modx->error->reset();

                  }
                }
              }

              // Увеличиваем старт
              $_SESSION['newProd']['start'] = $i;

              // Отдаём данные в JS
              $result = array('error' => $error,'progress' => ((($i) * 100)) / $amount,'amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Загрузка товаров','dataType' => 'newProd','class' => 'success','message' => 'Новых товаров было загружено');
              echo json_encode($result);

            } else {

              // Отдаём данные в JS
              $result = array('error' => 1,'progress' => 0, 'amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Загрузка товаров','dataType' => 'newProd','class' => 'warning','message' => 'Нет товаров для загрузки');
              echo json_encode($result);

            }
    }

    public function productsUpdate()
    {        
             //получаем необходимые обьекты
             $modx = $this->di->get("modx");

            // Иморт категорий
            if (!empty($_SESSION['updProd']['items'])) {

              $amount = $_SESSION['updProd']['amount'];
              $start = $_SESSION['updProd']['start'];
              $limit = $_SESSION['updProd']['start'] + $_SESSION['updProd']['limit'];

              // Если лимит выше общего количества - значит это последняя итерация
             if ($limit >= $amount) 
             {$limit = $amount;$status = 'stop';} 
             else {$status = 'next';}

              // Импорт данных
              for ($i = $start; $i < $limit; $i++) {
                  
                //Массив параметров для импорта по умолчанию 
                $arrayDef = array('published' => 1,'context_key' => 'web');

                //Формируем одну строку со всеми указанными параметрами в массив
                $importArray = $this->importArray( $_SESSION['updProd']['items'], $i ,$arrayDef);

                //Запускаем процессор и передаем ему массив параметров 
                $response = $modx->runProcessor('resource/update', $importArray);

                if ($response->response['success'] == false) {

                  foreach ($response->errors as $key => $value) {
                      
                     $modx->error->reset();
                     
                  }

                }
              }

              // Увеличиваем старт
              $_SESSION['updProd']['start'] = $i;

              // Отдаём данные в JS
              $result = array('error' => $error,'progress' => ((($i) * 100)) / $amount,'amount' => $amount,'uploaded' => $i,'status' => $status,'caption' => 'Обновление товаров','dataType' => 'updProd','class' => 'success','message' => 'Товаров было обновлено');
              echo json_encode($result);

            } else {

              // Отдаём данные в JS
              $result = array('error' => 1,'progress' => 0, 'amount' => 0,'uploaded' => 0,'status' => 'stop','caption' => 'Обновление товаров','dataType' => 'updProd','class' => 'warning','message' => 'Нет товаров для обновления');
              echo json_encode($result);

            }
    }

    public function finished()
    {
        unset($_SESSION['newCat'], $_SESSION['newProd'], $_SESSION['updProd'], $_SESSION['updCat']);
       
        $result = array('error' => 2,'class' => 'success','message' => 'Выгрузка окончена');
        echo json_encode($result);

        $modx = $this->di->get("modx");

        $modx->cacheManager->refresh();

    }

    public function array_values( $array )
    {
        $arrKey = array();
        $arrVal = array();

        if(!empty($array)){
            foreach ($array as $key => $value) {
            
                  foreach ($value as $k => $v) {

                     foreach ($v as $index => $data) {
                         
                         array_push( $arrKey, $index);
                         array_push( $arrVal, $data);

                     }   
                }
            }
            
            return $this->change_key($arrKey, $arrVal );
        }
        else{
             return 0;
        }

    }

   public  function change_key($arrKey, $arrVal ) {

        $result = array();
        foreach ($arrKey as $i => $k) {

            $result[$k][] = $arrVal[$i];

        }
        return  $result; 
    }

    public function importArray( $array, $iterator = 0, $mixed)
    {

            $result = array();

            foreach ($array as $i => $k) {

              if( $k[$iterator]){

                   $result[$i] = $k[$iterator];

              }

            }
            
            return  array_merge ($result, $mixed);

    }
public function isParrentCheck( $name )
  {
     if(!empty($name)){
        
        $name = explode(';',$name);

        return $name;

     }else{

         return $name;

     }
  }

 public function isCoincides ( $name )
 {
    $config = $this->di->get('config');

    $idParrent = $config['ImportConfig']['main_general_categoryID'];

    if($docs = $this->modx->getCollection('modResource', array('pagetitle' => $name))){
         
           foreach($docs as $doc){

              $idParrent = $doc->get('id');

           }

           return (int)$idParrent;

      }else{

      return (int)$idParrent;

      }
  }*/



}
?>