<?php
namespace Litvinenko\Combinatorics\Pdp\Generators\Recursive;

use Litvinenko\Combinatorics\Pdp\Helper;
class PdpPermutationGenerator extends \Litvinenko\Combinatorics\Common\Generators\Recursive\RegularSetGenerator
{
    protected $dataRules = array(
        'generating_elements' => 'required|array',
        'tuple_length'        => 'required|integer_strict',
        'initial_object'      => 'required|array'
    );

    protected function _getSuccessiveElements($tuple)
    {
        $result = [];

        // we assume that tuple contain \Litvinenko\Combinatorics\Pdp\Point objects
        $existingPointIds = $this->_getTuplePointIds($tuple);
        $allPdpPoints     = array_column($this->getGeneratingElements(), 'value');
        foreach($this->getGeneratingElements() as $element)
        {
            $point = $element['value'];
            if (!in_array($point->getId(), $existingPointIds))
            {
                if ( $point->isPickup() ||
                     $point->isDelivery() && in_array(Helper::getComplementaryPdpPoint($allPdpPoints, $point)->getId(), $existingPointIds)
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