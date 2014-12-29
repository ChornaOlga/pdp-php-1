<?php

namespace Litvinenko\Combinatorics\Common\Generators;

abstract class AbstractGenerator extends \Varien_Object
{
    // necessary data:
    //  generating_elements array

    abstract public function generateAll();
}
