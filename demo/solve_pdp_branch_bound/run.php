<?php
use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Common\App;

require_once '../../vendor/autoload.php';
require 'SolutionInfoCollector.php';

$pointInfoFile  = 'pdp_points.txt';
$pdpConfigFile  = 'pdp_config.ini';
$solverClass    = '\Litvinenko\Combinatorics\Pdp\Solver\BranchBoundSolver';
$metricsClass   = '\Litvinenko\Combinatorics\Pdp\Metrics\EuclideanMetric';
$evaluatorClass = '\Litvinenko\Combinatorics\Pdp\Evaluator\PdpEvaluator';

App::init();
$pointInfo = IO::readPointsFromFile($pointInfoFile);
$pdpConfig = IO::readConfigFromIniFile($pdpConfigFile);
// var_dump($pdpInfo);

$solver = new $solverClass(array_merge($pointInfo, $pdpConfig, [
    'evaluator' => new $evaluatorClass(['metrics'   => new $metricsClass])
    ]));
try
{
    $bestPath = $solver->getSolution()->getContent();

    echo "<pre>";
    $i = 0;
    foreach (App::getSingleton('\SolutionInfoCollector')->getStepsInfo() as $stepInfo)
    {
        echo IO::getReadableStepInfo($stepInfo, ++$i);
    }

    echo "\n\nfinal path: " . IO::getPathAsText($bestPath);
}
catch (\Exception $e)
{
    echo "Exception occured: \n" . $e->getMessage();
}
//var_dump($solver);

// $n = new \BranchBound\Node;
// $n->a();

//info about last step:
// \Litvinenko\Combinatorics\Pdp\IO::getReadableStepInfo(last(\Litvinenko\Common\App::getSingleton('\SolutionInfoCollector')->getStepsInfo()))