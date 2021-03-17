<?
 /**
  *  main_general_categoryID => Корневая категория
  *  main_step => Шаг - для обработки порционного кол-ва (снижает нагрузку на сервер)
  *  main_field_names_product => ТВ поля которые участвуют в выгрузке необходимо указать через запятую в array(price,weight,tvcolor,tvStyle итд) в точности как они и есть
  *  если это подключаемое поле то его нужно обозначать тем же именем как оно у Вас называется в админке. Важно - поля размещать строго порядку карты
  *  csv документа
  *  main_field_names_category - аналогично полю выше, только оно для категорий,
  *  main_file_name => 'test.csv' - файл откуда выгружать
  *  main_delimiter => '|' - разделитель строк
  *  main_unique_field_category => название уникального поля для категорий (код категории или артикул), должно быть из списка карты 
  *  main_unique_field_product => название уникального поля для продукта (код продутка или артикул), должно быть из списка карты 
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
   main_unique_field_category => 'article',
   main_unique_field_product => 'code',
   main_field_names_product => array(
      'article',
      'pagetitle',
      'in_stock',
      'price_in_rub_sales',
      'box_in_rub',
      'rrp_to_rub',
      'code',
      'full_product_description',
      'link_to_product_image',
      'size',
      'wallpaper_type',
      'roll_length',
      'picture_size',
      'premises',
      'wallpaper_texture',
      'base_material',
      'the_country',
      'manufacturer',
      'collection',
      'material',
      'pattern_repeat',
      'roll_width',
      'drawing',
      'color',
      'special properties',
      'style',
      'cover material'
   ),
   main_field_names_category => array(
      'article',
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

