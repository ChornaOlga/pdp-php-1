<?php
use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Combinatorics\Pdp\Helper;
use \Litvinenko\Common\App;
use \Litvinenko\Combinatorics\Pdp\Path;

require_once '../../vendor/autoload.php';
require 'SolutionInfoCollector.php';

$pointInfoFile  = 'pdp_points.txt';
$pdpConfigFile  = 'pdp_config.ini';
$solverClass    = '\Litvinenko\Combinatorics\Pdp\Solver\PreciseGenerationSolver';
$metricsClass   = '\Litvinenko\Combinatorics\Pdp\Metrics\EuclideanMetric';
$evaluatorClass = '\Litvinenko\Combinatorics\Pdp\Evaluator\PdpEvaluator';
$generationLogFile = '';

App::init();
$pointInfo = IO::readPointsFromFile($pointInfoFile);
$pdpConfig = IO::readConfigFromIniFile($pdpConfigFile);
// var_dump($pdpInfo);


$solver = new $solverClass(array_merge($pointInfo, $pdpConfig, [
    'evaluator' => new $evaluatorClass(['metrics'   => new $metricsClass])
    ]));
echo "<pre>\n";
try
{
    App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->start();
    $bestPath = $solver->getSolution();
    printf('Solution was obtained in %.4F seconds', App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->getTimeFromStart());

    echo "\n\ntotal paths generated:" .  count($solver->getGeneratedPointSequences()) . "\n";
    App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->start();

    if ($pdpConfig['log_solution'])
    {
        echo "all paths at last step:\n";
        foreach ($solver->getGeneratedPointSequences() as $pointSequence)
        {
        // $path = new Path(['points' => $pointSequence]);
            echo IO::getPathAsText($pointSequence) . ' ' . $solver->_getCost($pointSequence) .   "\n";
        }

        echo "\n\nnot loaded paths:\n";
        foreach (App::getSingleton('\SolutionInfoCollector')->getNotLoadedPaths() as $pointSequence)
        {
        // $path = new Path(['points' => $pointSequence]);
            echo IO::getPathAsText($pointSequence) . ' ' . $solver->_getCost($pointSequence) .   "\n";
        }

    }
    if ($generationLogFile)
    {
        file_put_contents('result.txt', App::getSingleton('\SolutionInfoCollector')->getLog());
    }
    // $i = 0;
    // foreach (App::getSingleton('\SolutionInfoCollector')->getStepsInfo() as $stepInfo)
    // {
    //     echo IO::getReadableStepInfo($stepInfo, ++$i);
    // }

    echo "\n\nfinal path: " . IO::getPathAsText($bestPath) . " with cost " . $solver->_getCost($bestPath) . "\n";


    printf('All other operations took %.4F seconds', App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->getTimeFromStart());
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

    echo "\n";