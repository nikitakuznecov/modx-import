<?
namespace Import\Base;
use Import\Base\Di;
use Import\Helpers\Messages;
use Import\Helpers\Common;

class Import{
  
    /**
     *  DI контейнер
     */
    private $di;

    private $parser;

    public $router;
    
    /**
     *  Конструктор Import принимает di контейнер
     */
    public function __construct($di) {

       $this->di = $di;
       $this->router = $this->di->get('router');

   }
   
  /**
   *  Единая точка входа - главный метод Run
   */
	public function run(){
    
        try {

            require_once(Common::replacePath( $_SERVER['DOCUMENT_ROOT'].'/import/assets/classes/Route.php' ));
            
            $routerDispatch = $this->router->dispatch(Common::getMethod(), Common::getPathUrl());
        
            if ($routerDispatch == null) {
                $routerDispatch = new DispatchedRoute('ErrorController:page404');
            }
             
            list($class, $action) = explode(':', $routerDispatch->getController(), 2);

            $controller = 'Import\\Assets\\Controllers\\' . $class; 
            $parameters = $routerDispatch->getParameters();
            call_user_func_array(array(new $controller($this->di),$action), $parameters);

        }catch (\Exception $e){

            echo Messages::messager($e->getMessage(), 'warning');
            exit;
        }
	}
}
?>