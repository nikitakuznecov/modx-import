<?php

 namespace Import\Base;
 use Import\Helpers\Messages;
 use Goodby\CSV\Import\Standard\Lexer;
 use Goodby\CSV\Import\Standard\Interpreter;
 use Goodby\CSV\Import\Standard\LexerConfig;

 class ParseCSV
 {
   
   protected $di; 

   protected $modx;

   private $categories;

   private $products;


   public function __construct($di)
   {
      $this->di = $di;
      $this->modx = $this->di->get('modx');
   }

   public function getCategoriesArray()
   {
       return $this->categories;
   }

   public function setCategoriesArray( $array )
   {
      $this->categories = $array;
   }

   public function getProductsArray()
   {
       return $this->products;
   }

   public function setProductsArray( $array )
   {
      $this->products = $array;
   }

   public function parse()
   {
      $conf = $this->di->get('config');

      $map_product = $conf['ImportConfig']['main_field_names_product'];

      $map_category = $conf['ImportConfig']['main_field_names_category'];

      $main_unique_field_product = $conf['ImportConfig']['main_unique_field_product'];

      $main_unique_field_category = $conf['ImportConfig']['main_unique_field_category'];

      $main_cell_category_in_product = $conf['ImportConfig']['main_cell_category_in_product'];

      $category = array();

      $product = array();

      try{

      if(!$map_product || empty($map_product)){
         throw new \ErrorException('Ошибка, в конфигурации не заполнена карта продукта');
      }

      if(!$map_category || empty($map_category)){
         throw new \ErrorException('Ошибка, в конфигурации не заполнена карта категорий');
      }

      if(!$main_unique_field_product || empty($main_unique_field_product)){
         throw new \ErrorException('Ошибка, в конфигурации не указан уникальный код товара');
      }

      if(!$main_unique_field_category || empty($main_unique_field_category)){
         throw new \ErrorException('Ошибка, в конфигурации не указан уникальный код категории');
      }

      if(!$main_cell_category_in_product || empty($main_cell_category_in_product)){
         throw new \ErrorException('Ошибка, в конфигурации не указано дополнительное значение уникального кода категории в товаре (main_cell_category_in_product)');
      }

      $config = new LexerConfig();

      $config->setDelimiter($conf['ImportConfig']['main_delimiter']);
      
      $lexer = new Lexer($config);

      $interpreter = new Interpreter();

      $interpreter->addObserver(function(array $row) use (&$category,&$product,&$map_product,&$map_category,&$main_unique_field_category,&$main_unique_field_product,&$main_cell_category_in_product ) {
        
         if($catValue = $this->byMapIntoAnArray($map_category,$row,$main_unique_field_category,$main_unique_field_category)){

            $category[] = $catValue;

         }
         if($prodValue = $this->byMapIntoAnArray($map_product,$row,$main_unique_field_product,$main_cell_category_in_product)){

            $product[] = $prodValue;

         }

      });

      $lexer->parse($this->checkPath( $conf['ImportConfig']['main_file_name'] ), $interpreter);

      $this->setCategoriesArray( $this->removeDublicates($category,$main_unique_field_category) );

      $this->setProductsArray( $product );

      }catch (\ErrorException $e){

         Messages::messager($e->getMessage(),true); 
      }
     
   }

   public function removeDublicates($arr,$unique)
   {
      $has = array();
      $output = array();

      foreach ( $arr as $key => $data )
      {
         if ( !in_array($data[$unique], $has) )
         {
            $has[] = $data[$unique];
            $output[] = $data;
         }
      }

      return $output;
   } 

   public function byMapIntoAnArray($map,$array,$uniqueCode,$ad_field)
   {
       try{
           
         if(!$map && !$array || empty($map) || empty($array)){throw new \ErrorException('Ошибка, входные данные карты в конфигурации не настроены либо настроены не верно');}
         
         $result = array();
         
         foreach($map as $key => $value){
            
            if($value !== 'none'){
               
               $result[$value] = $array[$key];

            }

         }
         if($result[$uniqueCode] && $result[$ad_field]){
            
            $result['state'] = $this->isCoincides ( $result[$uniqueCode] );
            
         }else{

            unset($result);

         }

         return $result;

       }catch (\ErrorException $e){

         Messages::messager($e->getMessage(),true); 
      }
   }

   public function checkPath ( $file_name )
   {
      if(is_readable ($this->replacePath( $file_name ))){

          $new_path = $this->replacePath( $file_name );
          return $new_path; 

      }else{

          return false;

      }
   }

   public function replacePath ( $path )
   {

          if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

              $path = str_replace('/', '\\'.'\\', $_SERVER['DOCUMENT_ROOT']."/import/template/files/".$path);

          } else {

              $path = $_SERVER['DOCUMENT_ROOT']."/import/template/files/".$path;
          }

          return $path;

   }

  public function isCoincides ( $uniqueCode ){

  
    $config = $this->di->get('config');

    $modx = $this->di->get("modx");

    $id = $config['ImportConfig']['main_general_categoryID'];

    if($resources = $modx->getCollection('modResource',array('link_attributes' => $uniqueCode))){

      foreach ($resources as $resource) {

         $id = $resource->get('id');

      }

      return true;

    }else{

      return false;

    }

  }
   

   public function isParrentCheck( $name )
   {
      if(!empty($name)){


         $name = explode(';',$name);

         return end($name);

      }else{

            return $name;

      }
   }

   }
?>