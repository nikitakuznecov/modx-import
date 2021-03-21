<?
 /**
  *  main_general_categoryID => Корневая категория
  *  main_step => Шаг - для обработки порционного кол-ва (снижает нагрузку на сервер)
  *  main_field_names_product => ТВ поля которые участвуют в выгрузке необходимо указать через запятую в array(tv1,tv2,tv3,tv4 итд) где цифра это id поля
  *  csv документа
  *  main_field_names_category - аналогично полю выше, только оно для категорий,
  *  main_file_name => 'test.csv' - файл откуда выгружать
  *  main_delimiter => '|' - разделитель строк
  *  main_cell_category_in_product - определяет позицию категории в строке параметров товара (для того чтобы отсеить на этапе сбора массива все товары у которых не указана категория)
  *  main_unique_field_category => название уникального поля для категорий (код категории или артикул), должно быть из списка карты 
  *  main_unique_field_product => название уникального поля для продукта (код продутка или артикул), должно быть из списка карты 
  *  main_download_path_images => путь куда будут загружены (временно) изображения
  *  main_cell_product_image - элемент массива где хранится изображение (элемент не будет учитываться в финальной выгрузке он необходим для загрузки картинки на сервер и последущей загрузки в галерею)
  *  Карта как должен выглядеть файл
  *  Название категории итд (параметры категории аналогично)| Название файла | итд (параметры файла в таком же порядке)
  *  main_import_relations => 1 - если есть повторения файлов в каких либо категориях он сделает на этот товар связь с этими категориями
  *  main_import_hide_resources => 0 - скрывает все категории и товары 
  *  параметры товара нужно выгружать по порядку следуя карте, то что выгружать не нужно прсто оставить 'none'. К примеру у товара 'none','none','pagetitle','none','price'
  *  в итоге сформируется массив 'pagetitle','price'
  */
return array(

   main_general_categoryID => '1',
   main_step => '100',
   main_file_name => 'test.csv',
   main_delimiter => '|',
   main_unique_field_category => 'pagetitle',
   main_unique_field_product => 'tv5',
   main_cell_category_in_product => 'tv16',
   main_cell_product_image => 'tv7',
   main_download_path_images => '/import/template/img/uploading/',
   main_field_names_product => array(
      'article',
      'pagetitle',
      'tv1',
      'tv2',
      'tv3',
      'tv4',
      'tv5',
      'tv6',
      'tv7',
      'tv8',
      'tv9',
      'tv10',
      'tv11',
      'tv12',
      'tv13',
      'tv14',
      'tv15',
      'tv16',
      'tv17',
      'tv18',
      'tv19',
      'tv20',
      'tv21',
      'tv22',
      'tv23',
      'tv24',
      'tv25'
   ),
   main_field_names_category => array(
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'pagetitle',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none',
      'none'
   ),
   main_cat_template => 2,
   main_prod_template => 3,
   main_import_relations => 1,
   main_import_hide_resources => 0, 
   main_login => 'admin',
   main_pass =>  'cbkmvfhbkkbjy'

);

?>

