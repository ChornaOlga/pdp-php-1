<?php

class SolutionInfoCollector extends \Litvinenko\Common\Object
{
    const LOG_FILE = 'solution_process.log';
    public function _construct()
    {
        $this->setStepsInfo([]);
        $this->setCurrentStepNo(-1);
        file_put_contents(self::LOG_FILE, 'begin solution' . PHP_EOL);
    }

    public function stepBegin($event)
    {
        $this->setCurrentStepNo($this->getCurrentStepNo() + 1);

        $this->setCurrentStepInfo([
            'root_node'                   => $event->getRootNode(),
            'active_nodes_at_the_begin'   => $event->getActiveNodes(),
            'best_full_node_at_the_begin' =>  $event->getCurrentBestFullNode()
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
        
        $log = \Litvinenko\Combinatorics\Pdp\IO::getReadableStepInfo($stepInfo, count($steps));
        file_put_contents(self::LOG_FILE, $log, FILE_APPEND);
    }

    public function cantLoad($event)
    {
        $pathsCouldNotBeLoaded   = isset($this->getCurrentStepInfo()['paths_could_not_be_loaded']) ? $this->getCurrentStepInfo()['paths_could_not_be_loaded'] : [];
        $pathsCouldNotBeLoaded[] = $event->getPointSequence();

        $stepInfo = array_replace($this->getCurrentStepInfo(), ['paths_could_not_be_loaded' => $pathsCouldNotBeLoaded]);
        $this->setCurrentStepInfo($stepInfo);
    }
}