<?php
use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Common\App;

require_once 'vendor/autoload.php';

const SOLUTION_METHOD_BRANCH_AND_BOUND = 'branch_and_bound';
const SOLUTION_METHOD_CUSTOM           = 'custom';

$pdpInfoFile        = 'pdp_points.txt';
$checkLoading       = true;
$loadingCheckerFile = 'pdphelper.exe';
$solutionMethod     = SOLUTION_METHOD_BRANCH_AND_BOUND;

class StepLogger
{
    protected $stepCount = 0;
    public function logStepBegin($event)
    {
        echo "\nbegin step " . ++$this->stepCount;
    }
}

App::init();
$pdpInfo = IO::readFromFile($pdpInfoFile);
// var_dump($pdpInfo);

$solverClass = ($solutionMethod == SOLUTION_METHOD_BRANCH_AND_BOUND) ? '\Litvinenko\Combinatorics\Pdp\Solver\BranchBoundSolver' : '\Pdp\Solver\CustomSolver';

$solver = new $solverClass([
    'depot'                => $pdpInfo->getDepot(),
    'points'               => $pdpInfo->getPoints(),
    'maximize_cost'        => false,
    'check_loading'        => $checkLoading,
    'loading_checker_file' => $loadingCheckerFile
]);

echo "\n\nfinal path: ".IO::getPathAsText($solver->getSolution()->getContent());
//var_dump($solver);

// $n = new \BranchBound\Node;
// $n->a();