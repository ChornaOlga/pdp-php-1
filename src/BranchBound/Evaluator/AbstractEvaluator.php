<?php
namespace Litvinenko\Combinatorics\BranchBound\Evaluator;

abstract class AbstractEvaluator extends \Litvinenko\Common\Object
{
    const BOUND_TYPE_OPTIMISTIC  = 'optimistic';
    const BOUND_TYPE_PESSIMISTIC = 'pessimistic';

    protected $dataRules = array(
        'avaliable_bound_types' => 'required|array'
    );

    public function _construct()
    {
        $this->setAvaliableBoundTypes([self::BOUND_TYPE_OPTIMISTIC, self::BOUND_TYPE_PESSIMISTIC]);
    }

    public function getBound(\Litvinenko\Combinatorics\BranchBound\Node $node, $boundType, array $additionalInfo = array())
    {
        if (in_array($boundType, $this->getAvaliableBoundTypes()))
        {
            return $this->_calculateBound($node, $boundType, $additionalInfo);
        }
    }

    abstract protected function _calculateBound(\Litvinenko\Combinatorics\BranchBound\Node $node, $boundType, array $additionalInfo = array());
}
