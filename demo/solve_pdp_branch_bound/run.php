<?php
use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Common\App;

require_once '../../vendor/autoload.php';
require 'SolutionInfoCollector.php';

$pointInfoFile  = '../pdp_points.txt';
$pdpConfigFile  = '../pdp_config.ini';
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
echo "<pre>";
try
{

    App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->start();
    $bestNode     = $solver->getSolution();
    $bestPath     = $bestNode->getContent();
    $solutionTime = App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->getTimeFromStart();

    printf('Solution was obtained in %.4F seconds', $solutionTime);

    App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->start();

    $stepInfo = App::getSingleton('\SolutionInfoCollector')->getStepsInfo();
    echo "\n\ntotal branchings made:" .  count($stepInfo) . "\n";

    // if ($pdpConfig['log_solution'])
    // {
    //     $i = 0;
    //     foreach ($stepInfo as $stepInfo)
    //     {
    //         echo IO::getReadableStepInfo($stepInfo, ++$i);
    //     }
    // }

    echo "\n\nfinal path: " . IO::getPathAsText($bestPath) . " with cost " . $bestNode->getOptimisticBound() . "\n";

    echo PHP_EOL . json_encode([
        'path'          => $bestPath->getPointIds(),
        'path_cost'     => $bestNode->getOptimisticBound(),
        'solution_time' => $solutionTime,
        'info'      => [
            'total_branchings' => count($stepInfo),
        ]
    ]);
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