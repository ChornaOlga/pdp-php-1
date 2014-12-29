<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

class PermutationWithRepetitionsGenerator extends RegularSetGenerator
{
    // necessary data: <parent data>

    protected function _getSuccessiveElements($tuple)
    {
        return $this->getGeneratingElements();
    }
}