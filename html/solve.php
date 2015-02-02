<?php

$avaliableSolutionMethods = ['gen', 'branch_bound'];

function run_php_file($filename)
{
    ob_start();
    include('myfile.php');
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}


function get_pdp_points_data_from_json($json)
{
    // foreach ($variable as $key => $value) {
    //     # code...
    // }
}

$response = [];
if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'])) && ($params instanceof stdClass))
{
    $data   = $params->data;
    $method = $params->method;

    if (in_array($method, $avaliableSolutionMethods))
    {
        $response['path'] = [1,2,3];
        $response['path_cost'] = 123;
    }
    else
    {
        $response['errors'][] = "Invalid solution method '{$method}'. Avaliable methods are '" . implode("','", $avaliableSolutionMethods) . "'";
    }
}
else
{
    $response['errors'][] = 'Input JSON is broken or absent';
}
// file_put_contents('123', var_export($_REQUEST, true));
file_put_contents('123', var_export($params, true));

echo json_encode($response);