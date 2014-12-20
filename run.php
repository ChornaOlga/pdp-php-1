<?php
require_once 'autoload.php';

const SOLUTION_METHOD_BRANCH_AND_BOUND = 'branch_and_bound';
const SOLUTION_METHOD_CUSTOM           = 'custom';

$pdpInfoFile        = 'pdp_points.txt';
$checkLoading       = true;
$loadingCheckerFile = 'pdphelper.exe';
$solutionMethod     = SOLUTION_METHOD_BRANCH_AND_BOUND;

$pdpInfo = Pdp_IO::read($pdpInfoFile);
// var_dump($pdpInfo);

$solverClass = ($solutionMethod == SOLUTION_METHOD_BRANCH_AND_BOUND) ? 'Pdp_Solver_BranchBound' : 'CustomSolver';
$solver      = new $solverClass($pdpInfo->depot, $pdpInfo->points, $checkLoading, $loadingCheckerFile);
var_dump($solver);