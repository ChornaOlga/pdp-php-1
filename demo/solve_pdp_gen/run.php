<?php
use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Combinatorics\Pdp\Helper;
use \Litvinenko\Common\App;
use \Litvinenko\Combinatorics\Pdp\Path;

require_once '../../vendor/autoload.php';
require 'SolutionInfoCollector.php';

$pointInfoFile  = '../pdp_points.txt';
$pdpConfigFile  = '../pdp_config.ini';
$solverClass    = '\Litvinenko\Combinatorics\Pdp\Solver\PreciseGenerationSolver';
$metricsClass   = '\Litvinenko\Combinatorics\Pdp\Metrics\EuclideanMetric';
$evaluatorClass = '\Litvinenko\Combinatorics\Pdp\Evaluator\PdpEvaluator';
$generationLogFile = '';
$solutionLogFile = 'solution.txt';

App::init();
$pointInfo = IO::readPointsFromFile($pointInfoFile);
$pdpConfig = IO::readConfigFromIniFile($pdpConfigFile);
// var_dump($pdpInfo);


$solver =  App::getSingleton($solverClass);
$solver->_construct();
$solver->addData(array_merge($pointInfo, $pdpConfig, [
    'evaluator' => new $evaluatorClass(['metrics'   => new $metricsClass])
    ])
);

echo "<pre>\n";
try
{
    App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->start();
    $bestPath = $solver->getSolution();
    $solutionTime = App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->getTimeFromStart();
    printf('Solution was obtained in %.4F seconds', $solutionTime);

    $totalGeneratedPaths = count($solver->getGeneratedPointSequences());
    echo "\n\ntotal paths generated:" .  $totalGeneratedPaths . "\n";
    App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->start();

    if ($pdpConfig['log_solution'] && $solutionLogFile)
    {
        $log =  "-------------- all paths at last step:\n";
        foreach ($solver->getGeneratedPointSequences() as $pointSequence)
        {
        // $path = new Path(['points' => $pointSequence]);
            $log .= IO::getPathAsText($pointSequence) . ' ' . $solver->_getCost($pointSequence) .   "\n";
        }

        $log .= "\n\n-------------not loaded paths:\n";
        foreach (App::getSingleton('\SolutionInfoCollector')->getNotLoadedPaths() as $pointSequence)
        {
        // $path = new Path(['points' => $pointSequence]);
            $log .= IO::getPathAsText($pointSequence) . ' ' . $solver->_getCost($pointSequence) .   "\n";
        }

        file_put_contents($solutionLogFile, $log);

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

    $bestCost = $solver->_getCost($bestPath);
    echo "\n\nfinal path: " . IO::getPathAsText($bestPath) . " with cost " . $bestCost . "\n";


    printf('All other operations took %.4F seconds', App::getSingleton('\Litvinenko\Combinatorics\Pdp\Helper\Time')->getTimeFromStart());

    echo PHP_EOL . json_encode([
        'path'          => $bestPath->getPointIds(),
        'path_cost'     => $bestCost,
        'solution_time' => $solutionTime,
        'info'      => [
            'total_generated_paths' => $totalGeneratedPaths,
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

    echo "\n";