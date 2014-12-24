<?php

namespace Pdp\Solver;
class BranchBoundSolver extends \BranchBound\AbstractSolver
{
    // Needed data: <as in parent> +
    //   depot:             \Pdp\Point
    //   points:             array of \Pdp\Point
    //   maximizeCost:       boolean
    //   checkLoading:       boolean
    //   loadingCheckerFile: string (path to checker file)

    public function __construct()
    {
        parent::__construct();

        $optimisticBound = ($this->getMaximizeCost()) ? 0 : PHP_INT_MAX;

        $this->setInitialNodeValue(new \Pdp\Path);
        $this->setInitialNodeOptimisticBound($optimisticBound);
        $this->setInitialNodePessimisticBound($optimisticBound);
    }

    protected function _compareNodes($firstNode, $secondNode)
    {
        // for now, just compare optimistic bounds
        return ($this->_compareCosts($firstNode->getOptimisticBound(), $secondNode->getOptimisticBound()));
    }

    protected function _generateChildrenOf($node)
    {
        foreach (range(1, 3) as $i)
        {
            $initialNode = new Node([
                'active'            => true,
                'value'             => (new \Pdp\Path)->setPoints($node->getValue() + [rand(1,10)]),
                'optimistic_bound'  => rand(1, 10),
                'pessimistic_bound' => rand(1, 10),
            ]);
        }
    }

    protected function _nodeIsCompleteSolution($node)
    {
        return (count($node->getValue()) == count($this->points));
    }
}