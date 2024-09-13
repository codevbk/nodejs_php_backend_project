<?php
require_once(dirname(__FILE__, 2)."/classes/database.class.php");
//require_once(dirname(__FILE__, 2)."/classes/error_exception_handler.class.php");
//require_once(dirname(__FILE__, 2)."/classes/logger.class.php");
$envFile = dirname(__FILE__, 2).'/.env';
use Classes\Database;
//use Classes\ErrorExceptionHandler;
//use Classes\Logger;
$database = new Database(); 
//$error_and_exception_handler = new ErrorExceptionHandler();
//$logger = new Logger();


if (file_exists($envFile)) {
    //echo $envFile;
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && $line[0] !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

$status = getenv('STATUS');
$configEnv = array();
//var_dump($_ENV);
switch ($status) {
    case 'DEVELOPMENT':
        $developmentKeys = [];
        foreach ($_ENV as $key => $value) {
            if (strpos($key, '_DEV') !== false) {
                if (strpos($key, '_DEV', -4) !== false) {
                    $developmentKeys[str_replace('_DEV','',$key)] = $value;
                }
            }
        }
        //echo 'Development Keys: ';
        //print_r($developmentKeys);
        $configEnv = $developmentKeys;
        ini_set('log_errors','On');
        ini_set('display_errors','On');
        ini_set('error_reporting', E_ALL );
        break;
    case 'PRODUCTION':
        $productionKeys = [];
        foreach ($_ENV as $key => $value) {
            if (strpos($key, '_PROD') !== false) {
                if (strpos($key, '_PROD', -5) !== false) {
                    $productionKeys[str_replace('_PROD','',$key)] = $value;
                }
            }
        }
        echo 'Production Keys: ';
        //print_r($productionKeys);
        $configEnv = $productionKeys;
        break;
    default:
        echo 'Invalid STATUS value';
}
//var_dump($configEnv);



?>