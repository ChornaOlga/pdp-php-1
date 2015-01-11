<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class AbstractGenerator extends \Litvinenko\Combinatorics\Common\Generators\AbstractGenerator
{
    protected $dataRules = array(
        'generating_elements' => 'required|array',
        'initial_object'      => 'not_null'
    );

    /**
     * Generates all child objects of given object and returns them
     *
     * @param  mixed $object
     *
     * @return array
     */
    abstract public function generateNextObjects($object);

    /**
     * Returns TRUE if object is full and can't have child objects (i.e., if we need to generate permutation of N objects and current permutation have N elements)
     *
     * @param  mixed $object
     *
     * @return bool
     */
    abstract public function objectIsComplete($object);

    /**
     * Returns ALL generated objects
     *
     * @return array
     */
    protected function _generateAll()
    {
        $this->_data['generatedObjects'] = [];
        $this->_generate($this->getInitialObject());

        return $this->_data['generatedObjects'];
    }

    protected function _generate($object)
    {
        if ($this->objectIsComplete($object))
        {
            $this->_data['generatedObjects'][] = $object;
        }
        else
        {
            foreach ($this->generateNextObjects($object) as $nextObject)
            {
                $this->_generate($nextObject);
            }
        }
    }
}
