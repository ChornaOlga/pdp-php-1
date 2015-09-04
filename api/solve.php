<?php
define('APP_ROOT_FOLDER',__DIR__.'/../');

require APP_ROOT_FOLDER.'/vendor/autoload.php';
require APP_ROOT_FOLDER.'/demo/PdpLauncher.php';

$launcher = new PdpLauncher;

$response = [];

$_REQUEST['params'] = '{
"specialParams":{
    "precise":"5",
    "weight_capacity":1000,
    "load_area":{"x":80, "y":80, "z":80}
},
"method":"gen",
"data":[["190.32","474.80","15.57","1.09","3.39","8.97"],["206.84","159.95","4.83","2.95","6.56","11.52"],["66.04","26.29","6.02","10.48","15.36","6.36"],["467.57","182.61","5.62","18.05","16.99","8.46"],["374.98","361.35",null,null,null,null],["450.28","434.02",null,null,null,null],["100.35","463.72",null,null,null,null],["411.28","492.88",null,null,null,null]]
}';
// var_dump($_REQUEST['params']);
if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'])) && ($params instanceof stdClass))
{
    $response = $launcher->getSolution($params->method, $params->data, json_decode(json_encode($params->specialParams), true));
}
else
{
    $response['errors'][] = 'Input JSON is broken or absent';
}
echo json_encode($response);