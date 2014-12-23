<?php

namespace Pdp\Solver;
// !!! Move this class to general solvers. PDP solver should extend it
class BranchBoundSolver extends \BranchBound\AbstractSolver
{
    public function __construct()
    {
        $this->_initialNodeValue            = new \Pdp\Path;
        $this->_initialNodeOptimisticBound  = ($this->maximizeCost) ? 0 : PHP_INT_MAX;
        $this->_initialNodePessimisticBound = $this->_initialNodeOptimisticBound;
    }

    // PDP - specific methods

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
                'value'             => new \Pdp\Path($node->getValue(), rand(1,10))
                'optimistic_bound'  => rand(1, 10)
                'pessimistic_bound' => rand(1, 10)
            ]);
        }
    }

    protected function _nodeIsCompleteSolution()
    {
        return (count($node->getValue()) == count($this->points));
    }

}