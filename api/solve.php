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
 // var_export($_REQUEST['params']); die();
 // $_REQUEST['params'] = '{"method":"gen","config":{"check_loading":true,"python_file":"/home/litvinenko/www/pdp-php/public_html/demo/pdphelper/pdphelper.py","precise":"5","weight_capacity":"1000","load_area":{"x":"100","y":"100","z":"100"}},"data":{"depot":["200","200"],"points":{"1":["154.95","461.47","8.77","17.82","17.93","9.85"],"2":["223.23","443.79","16.16","15.86","14.11","2.63"],"3":["239.50","357.05","18.32","12.83","9.71","14.85"],"4":["275.85","119.72","4.82","7.44","3.01","9.97"],"5":["165.85","192.29","8.88","6.79","3.92","1.98"],"6":["39.89","329.86","10.08","9.29","13.74","6.55"],"7":["453.02","133.29","13.08","11.02","7.29","9.30"],"8":["137.09","275.25","2.62","14.26","14.82","3.75"],"9":["255.20","104.50","2.01","4.95","6.11","19.05"],"10":["471.78","352.27","10.84","11.52","17.19","9.61"],"11":["195.99","320.85",null,null,null,null],"12":["34.86","468.21",null,null,null,null],"13":["118.22","150.03",null,null,null,null],"14":["221.97","311.98",null,null,null,null],"15":["256.31","164.27",null,null,null,null],"16":["306.96","435.06",null,null,null,null],"17":["259.63","137.56",null,null,null,null],"18":["313.09","229.30",null,null,null,null],"19":["302.33","307.53",null,null,null,null],"20":["428.36","12.87",null,null,null,null]}}}';

$response = [];
header('Content-Type: application/json');
if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'], true)) && (is_array($params))) {
    foreach ($params as $key => $param) {
        if (empty($param['data']['points'])) {
            continue;
        }

        if (isset($_SERVER['PHP_AUTH_USER']) && $_SERVER['PHP_AUTH_USER'] != 'admin') {
            $param['config']['time_limit'] = 300;
        }

        $response[$key] = $launcher->getSolution($param['data'], $param['config'], $param['method']);
    }

} else {
    header("HTTP/1.0 400 Bad request");
    $response['errors'] = 'Input JSON is broken or absent';
}

echo json_encode($response);