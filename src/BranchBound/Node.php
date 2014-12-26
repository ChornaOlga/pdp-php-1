<?php
namespace BranchBound;

class Node extends \Tree\Node\Node
{
    /**
     * @method string getOptimisticBound()
     * @method BranchBound\Node setOptimisticBound(float $optimisticBound)
     */

    public $dataObject;

    /**
     * Set/Get attribute wrapper. Calls Varien_Object
     *
     * @param   string $method
     * @param   array $args
     * @return  mixed
     */
    public function __call($method, $args)
    {
        $this->dataObject->__call($method, $args);
    }

    /**
     * @param array $data
     */
    public function __construct($data = null)
    {
        $this->dataObject = new \Varien_Object;
        $this->dataObject->setData($data);
    }

    public function getActiveChildren()
    {
        $result = [];
        foreach ($this->getChildren() as $child)
        {
            if ($child->getActive())
            {
                $result[] = $child;
            }
        }

        return $result;
    }
}