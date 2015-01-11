<?php
namespace Litvinenko\Combinatorics\Pdp\Evaluator;

class PdpEvaluator extends \Litvinenko\Combinatorics\BranchBound\Evaluator\AbstractEvaluator
{
    /**
     * Calculates OPTIMISTIC node bound for PDP permutation
     *
     * @param  \Litvinenko\Combinatorics\BranchBound\Node $node
     * @param  string                                     $boundType
     * @param  array                                      $additionalInfo
     *
     * @return float
     */
    protected function _calculateBound(\Litvinenko\Combinatorics\BranchBound\Node $node, $boundType, array $additionalInfo = array())
    {
        $result = null;

        // calculates only optimistic bounds
        if ($boundType == self::BOUND_TYPE_OPTIMISTIC)
        {
            $result = $node->getContent()->getCost();
        }

        return $result;
    }
}
