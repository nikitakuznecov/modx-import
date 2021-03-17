<?
namespace Import\Helpers;

class Messages{

  
  /**
   * Функция вывода сообщений в формате json  Messages::messager($resp), если вторым параметром передать true тогда первый параметр должен быть кодом ошибки Messages::messager('403', true);
   */
  public function messager($response, $err = false)
  {
      
      if($err == false){
          
          if(!empty($response)){
              
              echo json_encode($response);
              exit();
          }
          
      }else{
          
           echo json_encode(array('error'=> true,'response'=>$response));
           exit();
           
      }

  
  }
    
}
?>