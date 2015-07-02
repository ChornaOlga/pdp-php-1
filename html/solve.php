<?php
require '../vendor/autoload.php';
include '../demo/PdpLauncher.php';

$launcher = new PdpLauncher;

$response = [];
// $_REQUEST['params'] = '{"method":"gen","data":[["61.08","415.75","1.42","3.27","1.30","17.19"],["73.99","341.67","4.42","6.81","8.10","4.76"],["467.35","315.30","2.27","1.59","10.57","12.64"],["449.19","245.44","8.09","1.36","2.37","5.77"],["489.62","467.18",null,null,null,null],["259.99","381.93",null,null,null,null],["259.42","268.35",null,null,null,null],["118.80","185.17",null,null,null,null]]}';
if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'])) && ($params instanceof stdClass))
{
    $response = $launcher->getSolution($params->method, $params->data);
}
else
{
    $response['errors'][] = 'Input JSON is broken or absent';
}
echo json_encode($response);