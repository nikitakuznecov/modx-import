<?
require_once __DIR__ .'/../vendor/autoload.php';
require_once __DIR__ .'/../../config.core.php';
require_once __DIR__ .'/../../core/model/modx/modx.class.php';

use Import\Base\Import;
use Import\Base\Di;

try{

    //Include DI
    $di = new DI();
   
    $services = require __DIR__ . '/Service/Service.php';

    //Init modx
    $modx = new modX();
    $modx->initialize('mgr');
    $modx->getService('error','error.modError');
    $modx->setLogLevel(modX::LOG_LEVEL_FATAL);
    $modx->setLogTarget(XPDO_CLI_MODE ? 'ECHO' : 'HTML');
    $modx->error->message = null; // Обнуляем переменную

    //Add modx in services
    $di->set('modx', $modx);
    
    // Init services
    foreach ($services as $service) {
        $provider = new $service($di);
        $provider->init();
    }

    $config = $di->get('config');

    $user_name = $config['ImportConfig']['main_login'];

    $password = $config['ImportConfig']['main_pass'];

    $response = $modx->runProcessor('security/login', array('username' => $user_name, 'password' => $password));
    
    if ($response->isError()) {
        $modx->log(modX::LOG_LEVEL_ERROR, $response->getMessage());
        return;
    }

    //Include Base Configurator
    $import = new Import($di);
    
    //Start general method Run
    $import ->run();

}catch (\ErrorException $e) {

    echo $e->getMessage();

}
?>