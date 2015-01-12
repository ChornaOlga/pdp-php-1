<?php
namespace Litvinenko\Combinatorics\Common;

abstract class GenerationSolver extends AbstractSolver
{
    protected $dataRules = array(
        'maximize_cost' => 'required|boolean',

        'precise'       => 'required|float_strict',
        'generator'     => 'required|object:\Litvinenko\Combinatorics\Common\Generators\AbstractGenerator'
    );

    public function getSolution()
    {

    }
}