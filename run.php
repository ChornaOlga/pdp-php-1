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

class SolutionInfoCollector extends \Litvinenko\Common\Object
{

    public function _construct()
    {
        $this->setStepsInfo([]);
        $this->setCurrentStepNo(-1);
    }

    public function stepBegin($event)
    {
        $this->setCurrentStepNo($this->getCurrentStepNo() + 1);

        $this->setCurrentStepInfo([
            'root_node'                 => $event->getRootNode(),
            'active_nodes_at_the_begin' => $event->getActiveNodes(),
            'best_full_node_at_the_begin'    =>  $event->getCurrentBestFullNode()
            ]);
        // echo "\nbegin step " . ++$this->stepCount;
    }

    public function stepBranchingBegin($event)
    {
        $stepInfo = array_merge($this->getCurrentStepInfo(), ['branching_node' => $event->getBranchingNode()]);
        $this->setCurrentStepInfo($stepInfo);
        // echo "\stepBranchingBegin step " . ++$this->stepCount;
    }

    public function stepBranchingChildrenGenerated($event)
    {
        $stepInfo = array_merge($this->getCurrentStepInfo(), ['children_generated' => $event->getChildrenGenerated()]);
        $this->setCurrentStepInfo($stepInfo);
        // echo "\nbegin step " . ++$this->stepCount;
    }

    public function stepEnd($event)
    {
        $stepInfo = array_merge($this->getCurrentStepInfo(), [
            'best_full_node_at_the_end'    =>  $event->getCurrentBestFullNode(),
            'active_nodes_at_the_end' => $event->getActiveNodes()
            ]);

        $steps = $this->getStepsInfo();
        $steps[$this->getCurrentStepNo()] = $stepInfo;

        $this->setStepsInfo($steps);
    }
}

App::init();
$pdpInfo = IO::readFromFile($pdpInfoFile);
// var_dump($pdpInfo);

echo "<pre>";

$solverClass = ($solutionMethod == SOLUTION_METHOD_BRANCH_AND_BOUND) ? '\Litvinenko\Combinatorics\Pdp\Solver\BranchBoundSolver' : '\Pdp\Solver\CustomSolver';

$solver = new $solverClass([
    'depot'                => $pdpInfo->getDepot(),
    'points'               => $pdpInfo->getPoints(),
    'maximize_cost'        => false,
    'check_loading'        => $checkLoading,
    'loading_checker_file' => $loadingCheckerFile
]);

echo "\n\nfinal path: ".IO::getPathAsText($solver->getSolution()->getContent());


$i = 0;
foreach (App::getSingleton('\SolutionInfoCollector')->getStepsInfo() as $stepInfo)
{
    echo IO::getReadableStepInfo($stepInfo, ++$i);
}
//var_dump($solver);

// $n = new \BranchBound\Node;
// $n->a();

//info about last step:
// \Litvinenko\Combinatorics\Pdp\IO::getReadableStepInfo(last(\Litvinenko\Common\App::getSingleton('\SolutionInfoCollector')->getStepsInfo()))