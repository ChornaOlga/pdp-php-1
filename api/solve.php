<?php
require_once '../vendor/autoload.php';
$xmlConfigFile  = __DIR__.'/config.xml';

\Litvinenko\Common\App::init($xmlConfigFile);

$launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;

$response = [];

// $_REQUEST['params'] = '{
//     "config": {
//         "precise": "100",
//         "weight_capacity": 1000,
//         "load_area": {
//             "x": 80,
//             "y": 80,
//             "z": 80
//         }
//     },
//     "method": "gen",
//     "data": {
//         "depot": [
//             200,
//             200
//         ],
//         "points": {
//             "1":[
//                 "190.32",
//                 "474.80",
//                 "15.57",
//                 "1.09",
//                 "3.39",
//                 "8.97"
//             ],
//             "2":[
//                 "206.84",
//                 "159.95",
//                 "4.83",
//                 "2.95",
//                 "6.56",
//                 "11.52"
//             ],
//             "3":[
//                 "66.04",
//                 "26.29",
//                 "6.02",
//                 "10.48",
//                 "15.36",
//                 "6.36"
//             ],
//             "4":[
//                 "467.57",
//                 "182.61",
//                 "5.62",
//                 "18.05",
//                 "16.99",
//                 "8.46"
//             ],
//             "5":[
//                 "374.98",
//                 "361.35",
//                 null,
//                 null,
//                 null,
//                 null
//             ],
//             "6":[
//                 "450.28",
//                 "434.02",
//                 null,
//                 null,
//                 null,
//                 null
//             ],
//             "7":[
//                 "100.35",
//                 "463.72",
//                 null,
//                 null,
//                 null,
//                 null
//             ],
//             "8":[
//                 "411.28",
//                 "492.88",
//                 null,
//                 null,
//                 null,
//                 null
//             ]
//         }
//     }
// }';
// var_dump($_REQUEST['params']);
if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'], true)) && (is_array($params)))
{

    $response = $launcher->getSolution($params['data'], $params['config'], $params['method']);
}
else
{
    $response['errors'][] = 'Input JSON is broken or absent';
}
echo json_encode($response);