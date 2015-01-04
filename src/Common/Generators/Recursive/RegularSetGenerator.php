<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class RegularSetGenerator extends AbstractGenerator
{
    protected $dataRules = array(
        'generating_elements' => 'required|array',
        'tuple_length'        => 'required|integer_strict',
        'initial_object'      => 'required|array'
    );

    abstract protected function _getSuccessiveElements($tuple);

    public function _construct()
    {
        parent::_construct();
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