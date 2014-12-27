<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class Abstract extends Litvinenko\Combinatorics\Common\Generators\Abstract
{
    // necessary data: <parent data> +
    //   initial_object mixed

    /**
     * Generates all possible objects which can be after given object
     * @param mixed $object
     * @return array
     */
    abstract public function generateNextObject($object);

    /**
     * Returns TRUE if object is complete (i.e., if we need to generate permutation of N objects and current permutation have N elements)
     * @param type $object
     * @return type
     */
    abstract public function objectIsComplete($object);

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
            foreach ($this->generateNextObject($object) as $nextObject)
            {
                $result[] = $this->_generate($nextObject);
            }
        }

        return $result;
    }

}
