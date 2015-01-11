<?php
namespace Litvinenko\Combinatorics\Pdp\Generators\Recursive;

use Litvinenko\Combinatorics\Pdp\Helper;
class PdpPermutationGenerator extends \Litvinenko\Combinatorics\Common\Generators\Recursive\RegularSetGenerator
{
    protected $dataRules = array(
        'generating_elements' => 'required|array',
        'tuple_length'        => 'required|integer_strict',
        'initial_object'      => 'not_null|array',

        // PDP specific params
        'current_path'    => 'required|object:\Litvinenko\Combinatorics\Pdp\Path', // current PDP path
        'weight_capacity' => 'required|float_strict',                              // vehicle weight capacity
    );

    protected function _getSuccessiveElements($tuple)
    {
        $result = [];

        // we assume that tuple contain \Litvinenko\Combinatorics\Pdp\Point objects
        $currentPath  = $this->getCurrentPath();
        foreach($this->getGeneratingElements() as $element)
        {
            $point = $element['value'];

            // if current path does not contain this point
            if (!$currentPath->doesContain($point))
            {
                     // add pickup point if whether vehicle can take box at this point
                if ( $point->isPickup()   && (($currentPath->getCurrentWeight() + $point->getBoxWeight()) <= $this->getWeightCapacity())
                     ||
                     // add delivery point if corresponding pickup ALREADY exists in current path
                     $point->isDelivery() && $currentPath->doesContain($point->getPairId())
                   )
                {
                    $result[] = $element;
                }
            }
        }

        return $result;
    }

    protected function _getTuplePointIds($tuple)
    {
        $result = [];
        foreach($tuple as $element)
        {
            $result[] = $element['value']->getId();
        }

        return $result;
    }
}