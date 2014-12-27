<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class RegularSet extends Abstract
{
    // necessary data: <parent data> +
    //   tuple_length int

    abstract protected function _getSuccessiveElements($tuple);

    public function __construct($data)
    {
        parent::__construct($data);

        $this->setInitialObject([]);
    }

    public function generateNextObject($tuple)
    {
        $result = [];
        foreach ($this->_getSuccessiveElements($tuple) as $newElement)
        {
            $newTuple   = $tuple;
            $newTuple[] = $newElement;
            $result[]   = $newTuple;
        }
    }

    public function objectIsComplete($tuple)
    {
        return (count($tuple) == $this->getTupleLength());
    }
}