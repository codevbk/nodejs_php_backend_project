<?php
/* */
require_once(dirname(__FILE__, 3)."/config/config.php");
$rootPath = dirname(__FILE__, 3);
/* */
$argumentCount = $_SERVER['argc']; // argument count
$argumenList = $_SERVER['argv'][1]; // argument list
if ($argumentCount > 1) {
    $requestKeyArray = array("test_id","test_name");
    $requestDataArray = array();
    $jsonArray = array();

    $jsonRequestData = json_encode($argumenList, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $requestData = json_decode(str_replace('\"', '"', $argumenList), true);
    /* */
    /* */
    $output = array();
    if(count($requestData) > 0){
        $checkError = False;
        $checkErrorIndex = False;
        $checkArrayCounts = array();
        foreach($requestData as $requestDataKey => $requestDataValue){
            $requestKeyFound = array_search($requestDataKey, $requestKeyArray);
            if($requestKeyFound !== False && is_int($requestKeyFound) == True){
                $requestDataArray[$requestDataKey] = $requestDataValue;
                /* */
            }else{
                if($checkError == False){
                    $checkError = True;
                    $checkErrorIndex = $requestDataKey;
                    /* */
                }
            }
        }
            /* */
        if($checkError == True){
            /* */
        }else{
            /* */
            $hostname = $configEnv["DB_HOST"];
            $username = $configEnv["DB_USERNAME"];
            $password = $configEnv["DB_PASSWORD"];
            $databaseName = $configEnv["DB_DATABASE"];
            $port = "3306";
            $encoding = "utf8";
            $ssl = false;
            
            $database->connect($hostname,$databaseName,$username,$password,$encoding,$port,$ssl); 
            /* */
            $requestConditionArray = array();
            /* */
            $whereConditions = array();
            foreach($requestDataArray as $requestDataKey => $requestDataValue){
                
                /* */
                if($requestDataKey === 'test_id'){
                    $whereConditions[] = "(`id` = '".$requestDataValue."')";
                }
                if($requestDataKey === 'test_name'){
                    $whereConditions[] = "(`name` = '".$requestDataValue."')";
                }  
               /* */
            }
            /* */
            /* */
            $listFilteredTests = $database->select(null,array("id","name"),'test_table',implode(" AND ", $whereConditions));
            //$database->debug();
            /* */
            /* */
            /* */
            $output["code"] = 200;
            $output["status"] = "OK";
            $output["message"] = "Data Fetched";
            $output["data"] = array();
            $outputData = array();
            foreach($listFilteredTests as $listTest){
                /* */
                $tempData = array();
                $tempData["TestID"] = $listTest['id'];
                $tempData["TestName"] = $listTest['name'];
                /* */

                /* */

                /* */
                $outputData[] = $tempData;
            }
            $output["data"] = $outputData;
            /* */
        }


    /* */
    }else{
        /* */
        $hostname = $configEnv["DB_HOST"];
        $username = $configEnv["DB_USERNAME"];
        $password = $configEnv["DB_PASSWORD"];
        $databaseName = $configEnv["DB_DATABASE"];
        $port = "3306";
        $encoding = "utf8";
        $ssl = false;
        
        $database->connect($hostname,$databaseName,$username,$password,$encoding,$port,$ssl); 

        /* */
        $listTests = $database->select(null,array("id","name"),'test_table',null);
        /* */

        /* */
        $output["code"] = 200;
        $output["status"] = "OK";
        $output["message"] = "Data Fetched";
        $output["data"] = array();
        $outputData = array();
        foreach($listTests as $listTest){
            /* */
            $tempData = array();
            $tempData["TestID"] = $listTest['id'];
            $tempData["TestName"] = $listTest['name'];
            /* */

            /* */

            /* */
            $outputData[] = $tempData;
        }
        $output["data"] = $outputData;
        /* */
    /* */
    }
    /* */
    $jsonData = json_encode($output, JSON_PRETTY_PRINT);
    echo $jsonData.PHP_EOL;
} else {
    echo "No arguments provided.";
}
/*
if(isset($error_and_exception_handler)){
    if($error_and_exception_handler->handler == TRUE){
       
    }
}
*/
exit(0);

?>