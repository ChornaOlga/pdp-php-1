<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class RegularSetGenerator extends AbstractGenerator
{
    /**
     * Generating_elements MUST be array, where each generating elements looks like
     * $someGeneratingElement = [
     *     'id'                  => <generating element UNIQUE ID. Needed because generating elements can have the same combinatorial value and we must differentiate them>,
     *     'value'               => <real object CORRESPONDING to generating element. Can be any (object/array/stream... whatever you like). NOT USED in generator, but used by its caller).>,
     *     'combinatorial_value' => <combinatorial value USED by generator. It's combinatorial representation of real object>
     * ];
     *
     * For example,
     * $someGeneratingElement = [
     *     'id'                  => $point->getId(),                // just ID
     *     'value'               => $point,                         // REAL point object
     *     'combinatorial_value' => $point->getCombinatorialValue() // combinatorial representation of real point. For point it can be point number (1,2,...)
     * ];
     *
     * @var array
     */
    protected $dataRules = array(
        'generating_elements' => 'not_null|array',
        'tuple_length'        => 'required|integer_strict',
        'initial_object'      => 'not_null|array'
    );

    abstract protected function _getSuccessiveElements($tuple);

    public function _construct()
    {
        parent::_construct();
        $this->setInitialObject([]);
    }

    public function generateNextObjects($tuple)
    {
        $this->validate();

        $result = [];
        foreach ($this->_getSuccessiveElements($tuple) as $newElement)
        {
            $result[] = array_merge($tuple, [$newElement]);
        }

        return $result;
    }

    public function objectIsComplete($tuple)
    {
        return (count($tuple) == $this->getTupleLength());
    }
}