<?php
namespace BranchBound;

class AbstractSolver
{
    protected $_initialNodeValue;
    protected $_initialNodeOptimisticBound;
    protected $_initialNodePessimisticBound;

    abstract protected function _compareNodes($firstNode, $secondNode);
    abstract protected function _generateLeafsOf($node);
    abstract protected function _nodeIsCompleteSolution($node);

    public function getSolution()
    {
        $rootNode = new Node;
        $initialNode = new Node([
            'active'            => true,
            'value'             => $this->_initialNodeValue,             // this property must be initialized before (i.g., in constructor)
            'optimistic_bound'  => $this->_initialNodeOptimisticBound,   // this property must be initialized before (i.g., in constructor)
            'pessimistic_bound' => $this->_initialNodePessimisticBound,  // this property must be initialized before (i.g., in constructor)
        ]);

        $rootNode->addChild($initialNode);
        $currentBestNode = $initialNode;

        while ($activeNodes = $rootNode->getActiveNodes())
        {
            $branchingNode = $this->_getBestNodeFrom($activeNodes);
            $branchingNode->setActive(false);

            $branchingNode->setChildren($this->_generateChildrenOf($branchingNode));
            foreach ($branchingNode->getChildren() as $newNode)
            {
                // if new node is better (or has better evaluation) than current best node
                if ($this->_compareNodes($newNode, $currentBestNode()) > -1)
                {
                    ($this->_nodeIsCompleteSolution($node)) ? $currentBestNode = $newNode : $newNode->setActive(true);
                }
                else
                {
                    $newNode->setActive(false);
                }
            }
        }
    }

    protected function _getBestNodeFrom($nodes)
    {
        $bestNode = reset($nodes);
        foreach ($nodes as $node)
        {
            if ($this->_compareNodes($node, $bestNode))
            {
                $bestNode = $node;
            }
        }

        return $bestNode;
    }
}
