<?php
namespace Litvinenko\Combinatorics\Pdp\Solver;

use Litvinenko\Combinatorics\BranchBound\Node;
use Litvinenko\Combinatorics\Pdp\Path;

class BranchBoundSolver extends \Litvinenko\Combinatorics\BranchBound\AbstractSolver
{
    // Needed data: <as in parent> +
    //   depot:             \Pdp\Point
    //   points:             array of \Pdp\Point
    //   maximizeCost:       boolean
    //   checkLoading:       boolean
    //   loadingCheckerFile: string (path to checker file)

    public function __construct($data)
    {
        parent::__construct($data);

        $optimisticBound = ($this->getMaximizeCost()) ? 0 : PHP_INT_MAX;

        $this->setInitialNodeContent(new Path);
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
        $result = [];
        if (!$this->_nodeIsCompleteSolution($node))
        {
            $unusedPointsCount = count($this->getPoints()) - $node->getContent()->getPointsCount();
            for ($i = 1; $i <= $unusedPointsCount; $i++)
            {
                $newPath  = new Path;
                $points   = $node->getContent()->getPoints();
                $points[] = $this->getPoints()[$i];

                $result[] = new Node([
                    'content'           => $newPath->setPoints($points),
                    'optimistic_bound'  => ($node->getOptimisticBound() == PHP_INT_MAX) ? rand(1, 10) : $node->getOptimisticBound() + rand(1, 10)
                    // 'pessimistic_bound' => rand(1, 10),
                ]);
            }
        }
        return $result;
    }

    protected function _nodeIsCompleteSolution($node)
    {
        return ($node->getContent()->getPointsCount() == count($this->getPoints()));
    }
}