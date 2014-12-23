<?php
// use Tree\Node\Node;
// namespace BranchBound;

// class Node extends Node
// {
//     protected $bound;

//     function setBound()
//     {
//         $this->bound = $bound;
//         return $this;
//     }

//     function getBound()
//     {
//         return $this->bound;
//     }
// }


// class BranchBound_Tree
// {

// }

namespace Pdp\Solver;

class BranchBoundSolver extends AbstractSolver
{
    protected $tree;

    protected function _init()
    {
        // $this->tree = new BranchBoundTree;
        // $this->tree->
    }

    public function getSolution()
    {
        $this->_init();
        // while (/* there is vertex with better cost  */)
        // {
        //     // get bounds for all active leaves
        //     // 
        //     // $subsets = $this->getPartition(/*$this->tree*/);
        // }
    }


    protected function solve()
    {

    }
}