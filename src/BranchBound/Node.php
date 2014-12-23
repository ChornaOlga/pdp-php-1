<?php
namespace BranchBound;

// use varien object!!!

class Node extends \Tree\Node\Node
{
    /**
     * @method string getOptimisticBound()
     * @method BranchBound\Node setOptimisticBound(float $optimisticBound)
     */

    protected $dataObject;

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
        $this->dataObject = new Varien_Object;
        $this->dataObject->setData($data);
    }

   /* function setOptimisticBound()
    {
        $this->optimisticBound = $optimisticBound;
        return $this;
    }

    function getOptimisticBound()
    {
        return $this->optimisticBound;
    }

    function setPessimisticBound()
    {
        $this->pessimisticBound = $pessimisticBound;
        return $this;
    }

    function getPessimisticBound()
    {
        return $this->pessimisticBound;
    }

    function setId()
    {
        $this->id = $id;
        return $this;
    }

    function getId()
    {
        return $this->id;
    }*/
}