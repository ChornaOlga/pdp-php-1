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
 // $_REQUEST['params'] = '{"method":"gen","config":{"total_pair_count":10,"check_loading":true,"python_file":"/home/litvinenko/www/pdp-php/public_html/demo","precise":"5","weight_capacity":"1000","load_area":{"x":"100","y":"100","z":"100"}},"data":{"depot":["200","200"],"points":{"1":["354.86","282.76","9.06","9.57","6.62","10.92"],"2":["243.03","344.68","17.56","11.70","11.82","7.77"],"3":["359.16","371.86","19.69","5.80","19.81","14.18"],"4":["314.16","455.13","18.57","2.95","11.18","11.96"],"5":["26.33","15.90","2.44","6.76","3.88","2.62"],"6":["128.26","271.05","15.07","1.92","12.33","9.95"],"7":["56.28","412.94","11.94","5.77","3.23","2.64"],"8":["66.10","117.26","11.83","17.01","8.79","1.40"],"9":["95.58","356.97","2.51","4.79","9.83","19.25"],"10":["342.18","422.25","19.63","11.12","5.16","12.11"],"11":["207.75","354.50",null,null,null,null],"12":["48.81","32.19",null,null,null,null],"13":["287.33","310.81",null,null,null,null],"14":["314.43","397.39",null,null,null,null],"15":["244.91","272.56",null,null,null,null],"16":["61.05","188.42",null,null,null,null],"17":["69.28","117.59",null,null,null,null],"18":["341.70","24.72",null,null,null,null],"19":["311.09","330.03",null,null,null,null],"20":["479.31","332.75",null,null,null,null]}}}';

if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'], true)) && (is_array($params)))
{
    $response = $launcher->getSolution($params['data'], $params['config'], $params['method']);
}
else
{
    $response['errors'][] = 'Input JSON is broken or absent';
}
echo json_encode($response);