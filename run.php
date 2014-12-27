<?php
require_once 'vendor/autoload.php';
use \Pdp\IO;

const SOLUTION_METHOD_BRANCH_AND_BOUND = 'branch_and_bound';
const SOLUTION_METHOD_CUSTOM           = 'custom';

$pdpInfoFile        = 'pdp_points.txt';
$checkLoading       = true;
$loadingCheckerFile = 'pdphelper.exe';
$solutionMethod     = SOLUTION_METHOD_BRANCH_AND_BOUND;

$pdpInfo = IO::read($pdpInfoFile);
// var_dump($pdpInfo);

$solverClass = ($solutionMethod == SOLUTION_METHOD_BRANCH_AND_BOUND) ? '\Pdp\Solver\BranchBoundSolver' : '\Pdp\Solver\CustomSolver';

$solver = new $solverClass([
    'depot'                => $pdpInfo->getDepot(),
    'points'               => $pdpInfo->getPoints(),
    'maximize_cost'        => false,
    'check_loading'        => $checkLoading,
    'loading_checker_file' => $loadingCheckerFile
]);

foreach ($solver->getSolution()->dataObject->getContent()->getPoints() as $point)
{
    echo $point->getId(). " ";
}
// var_dump($solver);

// $n = new \BranchBound\Node;
// $n->a();