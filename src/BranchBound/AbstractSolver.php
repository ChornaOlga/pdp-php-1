<?php
namespace BranchBound;

abstract class AbstractSolver extends \Common\AbstractSolver
{
    // necessaryData = <as in parent> +
    //  initial_node_value:             mixed
    //  initial_node_optimistic_bound:  float
    //  initial_node_pessimistic_bound: float

    abstract protected function _compareNodes($firstNode, $secondNode);
    abstract protected function _generateChildrenOf($node);
    abstract protected function _nodeIsCompleteSolution($node);

    public function getSolution()
    {
        $rootNode = new Node;
        $initialNode = new Node([
            'active'            => true,
            'value'             => $this->getInitialNodeValue(),
            'optimistic_bound'  => $this->getInitialNodeOptimisticBound(),
            'pessimistic_bound' => $this->getInitialNodePessimisticBound(),
        ]);

        $rootNode->addChild($initialNode);
        $currentBestNode = $initialNode;

        while ($activeNodes = $rootNode->getActiveChildren())
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

        return $currentBestNode;
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
