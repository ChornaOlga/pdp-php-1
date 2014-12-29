<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class RegularSetGenerator extends AbstractGenerator
{
    // necessary data: <parent data> +
    //   tuple_length        int
    //   generating_elements array
    //
    //   initial_object is initialized in constructor

    abstract protected function _getSuccessiveElements($tuple);

    public function __construct($data)
    {
        parent::__construct($data);

        $this->setInitialObject([]);
    }

    public function generateNextObjects($tuple)
    {
        $result = [];
        foreach ($this->_getSuccessiveElements($tuple) as $newElementId => $newElement)
        {
            $newTuple   = $tuple;
            $newTuple[]  =  $newElement;

            $result[]   = $newTuple;
        }

        return $result;
    }

    public function objectIsComplete($tuple)
    {
        return (count($tuple) == $this->getTupleLength());
    }
}