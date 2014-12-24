<?php
require_once 'vendor/autoload.php';

const SOLUTION_METHOD_BRANCH_AND_BOUND = 'branch_and_bound';
const SOLUTION_METHOD_CUSTOM           = 'custom';

$pdpInfoFile        = 'pdp_points.txt';
$checkLoading       = true;
$loadingCheckerFile = 'pdphelper.exe';
$solutionMethod     = SOLUTION_METHOD_BRANCH_AND_BOUND;

$pdpInfo = \Pdp\IO::read($pdpInfoFile);
// var_dump($pdpInfo);

$solverClass = ($solutionMethod == SOLUTION_METHOD_BRANCH_AND_BOUND) ? '\Pdp\Solver\BranchBoundSolver' : '\Pdp\Solver\CustomSolver';

$solver = new $solverClass([
    'depot'                => $pdpInfo->getDepot(),
    'points'               => $pdpInfo->getPoints(),
    'maximize_cost'        => false,
    'check_loading'        => $checkLoading,
    'loading_checker_file' => $loadingCheckerFile
]);

var_dump($solver->getSolution()->dataObject->getValue());
// var_dump($solver);

// $n = new \BranchBound\Node;
// $n->a();