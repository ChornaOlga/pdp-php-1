<?php

$avaliableSolutionMethods     = ['gen' => 'cd ../demo/solve_pdp_gen && php run.php', 'branch_bound' => 'php ../demo/solve_pdp_branch_bound/run.php'];
$pdpPointsFile                = '../demo/pdp_points.txt';
$runGenMethodFilename         = '../demo/solve_pdp_gen/run.php';
$runBranchBoundMethodFilename = '../demo/solve_branch_bound/run.php';

function run_php_file($filename)
{
    ob_start();
    include('myfile.php');
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}


function write_pdp_points_content($filename, $data)
{
    $content = count($data) . PHP_EOL;

    $i = 1;
    foreach ($data as $row)
    {
        $content .= $i++ . ' ' . implode(' ', $row) . PHP_EOL;
    }
    $content .= 'depot 200 200';

    file_put_contents($filename, $content);
}

$response = [];
// $_REQUEST['params'] = '{"method":"gen","data":[["61.08","415.75","1.42","3.27","1.30","17.19"],["73.99","341.67","4.42","6.81","8.10","4.76"],["467.35","315.30","2.27","1.59","10.57","12.64"],["449.19","245.44","8.09","1.36","2.37","5.77"],["489.62","467.18",null,null,null,null],["259.99","381.93",null,null,null,null],["259.42","268.35",null,null,null,null],["118.80","185.17",null,null,null,null]]}';
if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'])) && ($params instanceof stdClass))
{
    $data   = $params->data;
    $method = $params->method;

    if (in_array($method, array_keys($avaliableSolutionMethods)))
    {
        write_pdp_points_content($pdpPointsFile, $data);

        $solutionCmdCommand = $avaliableSolutionMethods[$method];
        if (($resultRaw = exec($solutionCmdCommand)) &&  ($result = json_decode($resultRaw)) && ($result instanceof stdClass))
        {
           $response['path']      = $result->path;
           $response['path_cost'] = $result->path_cost;
           $response['info']      = $result->info;
        }
        else
        {
            // file_put_contents('123', var_export($_REQUEST['params'], true));
           $response['errors'][] = "Internal error. Cannot run solution script or its output is incorrect";
        }
    }
    else
    {
        $response['errors'][] = "Invalid solution method '{$method}'. Avaliable methods are '" . implode("','", array_keys($avaliableSolutionMethods)) . "'";
    }
}
else
{
    $response['errors'][] = 'Input JSON is broken or absent';
}
echo json_encode($response);