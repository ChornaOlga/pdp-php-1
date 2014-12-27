<?php

namespace Litvinenko\Combinatorics\Common\Generators\Recursive;

abstract class PermutationWithRepetitions extends RegularSet
{
    protected function _getSuccessiveElements($tuple)
    {
        return $this->getGeneratingElements();
    }
}