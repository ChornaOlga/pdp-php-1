<?php
namespace Litvinenko\Combinatorics\Pdp\Generators\Recursive;

use Litvinenko\Common\App;
class PrecisedPdpPermutationGenerator extends PdpPermutationGenerator
{
    protected $dataRules = array(
        'generating_elements' => 'required|array',
        'tuple_length'        => 'required|integer_strict',
        'initial_object'      => 'not_null|array',

        // PDP specific params
        'weight_capacity' => 'required|float_strict',                              // vehicle weight capacity

        // this class specific params
        'precise' => 'required|float_strict',
        'metrics' => 'required|object:\Litvinenko\Combinatorics\Pdp\Metrics\AbstractMetric',

        'log_steps' => 'required|boolean'
    );

    protected function _getSuccessiveElements($tuple)
    {
        $result = [];

        if ($allCandidates = parent::_getSuccessiveElements($tuple))
        {
            $childrenCount = max(1, round($this->getPrecise() * count($allCandidates)/100));
            $result = $this->_getNElementsNearestTo(last($tuple), $allCandidates, $childrenCount);
        }

        return $result;
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
                if ($this->getLogSteps())
                {
                    App::dispatchEvent("new_path_generated", ['tuple' => $nextObject]);
                }
                $this->_generate($nextObject);
            }
        }
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

    protected function _getNElementsNearestTo($targetElement, $allElements, $n)
    {
        $metrics = $this->getMetrics();

        $distancesToTarget = [];
        foreach ($allElements as $element)
        {
            $distancesToTarget[$element['id']] = $metrics->getDistanceBetweenPoints($element['value'], $targetElement['value']);
        }

        $result = [];
        foreach (array_keys($this->_getNMinArrayElements($distancesToTarget, $n)) as $id)
        {
            $result[] = $allElements[$id];
        }

        return $result;
    }

    protected function _getNMinArrayElements($arr, $n)
    {
        $keys  = array_keys($arr);
        $point = $arr[$keys[0]];

        for ($i = 0; $i < $n; ++$i)
        {
            $min    = null;
            $minKey = null;

            for($j = $i; $j < count($arr); ++$j)
            {
                if (null === $min || $arr[$keys[$j]] < $min)
                {
                    $minKey = $keys[$j];
                    $min    = $arr[$keys[$j]];
                }
            }

            $arr[$minKey]   = $arr[$keys[$i]];
            $arr[$keys[$i]] = $min;
        }

        return array_slice($arr, 0, $n);
    }

}