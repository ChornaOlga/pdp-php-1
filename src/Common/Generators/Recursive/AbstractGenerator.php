<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class AbstractGenerator extends \Litvinenko\Combinatorics\Common\Generators\AbstractGenerator
{
    // necessary data: <parent data> +
    //   initial_object mixed

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
    public function generateAll()
    {
        return $this->_generate($this->getInitialObject());
    }

    protected function _generate($object)
    {
        if ($this->objectIsComplete($object))
        {
            $result = $object;
        }
        else
        {
            $result = [];
            foreach ($this->generateNextObjects($object) as $nextObject)
            {
                $result[] = $this->_generate($nextObject);
            }
        }

        return $result;
    }

}
