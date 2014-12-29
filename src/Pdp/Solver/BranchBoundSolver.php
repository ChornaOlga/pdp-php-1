<?php
namespace Litvinenko\Combinatorics\Pdp\Solver;

use Litvinenko\Combinatorics\BranchBound\Node;
use Litvinenko\Combinatorics\Pdp\Path;
use Litvinenko\Combinatorics\Pdp\Point;
use Litvinenko\Combinatorics\Common\Generators\Recursive\PermutationWithRepetitionsGenerator as Generator;

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
            $generator = new Generator([
                'tuple_length'        => Point::getPointCount($this->getPoints()),
                'generating_elements' => $this->_getGeneratorDataFromPoints($this->getPoints())
            ]);

            $points    = $this->_getGeneratorDataFromPoints($node->getContent()->getPoints());
            $newPointSequences = $this->_getPointsFromGeneratorData($generator->generateNextObjects($points));

            foreach ($newPointSequences as $newPointSequence)
            {
                $result[] = new Node([
                    'content'           => new Path(['points' => $newPointSequence]),
                    'optimistic_bound'  => ($node->getOptimisticBound() == PHP_INT_MAX) ? rand(1, 10) : $node->getOptimisticBound() + rand(1, 10)
                    // 'pessimistic_bound' => rand(1, 10),
                ]);
            }
        }
        return $result;
    }

    protected function _nodeIsCompleteSolution($node)
    {
        return ($node->getContent()->getPointsCount() == Point::getPointCount($this->getPoints()));
    }

    protected function _getGeneratorDataFromPoints($points)
    {
        $result = [];
        foreach ($points as $point)
        {
            $result[] = [
                'id'                  => $point->getId(),
                'value'               => $point,
                'combinatorial_value' => $point->getCombinatorialValue()
            ];
        }

        return $result;
    }

    protected function _getPointsFromGeneratorData($generatorData)
    {
        $result = [];
        foreach ($generatorData as $pointSequence)
        {
            $sequence = [];
            foreach ($pointSequence as $point)
            {
                $sequence[] = $point['value'];
            }
            $result[] = $sequence;
        }

        return $result;
    }

}