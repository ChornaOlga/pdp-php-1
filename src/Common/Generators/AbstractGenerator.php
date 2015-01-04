<?php

namespace Litvinenko\Combinatorics\Common\Generators;

abstract class AbstractGenerator extends \Litvinenko\Common\Object
{
    protected $dataRules = array(
        'generating_elements' => 'required|array'
    );

    abstract public function generateAll();
}
