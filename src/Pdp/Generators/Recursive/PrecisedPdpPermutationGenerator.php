<?php
namespace Litvinenko\Combinatorics\Pdp\Generators\Recursive;

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
        'metrics' => 'required|object:\Litvinenko\Combinatorics\Pdp\Metrics\AbstractMetric'
    );

    protected function _getSuccessiveElements($tuple)
    {
        $result = [];

        if ($allCandidates = parent::_getSuccessiveElements($tuple))
        {
            $childrenCount = max(1, round($this->getPrecise() * count($allCandidates)));
            $result = $this->_getNElementsNearestTo(last($tuple), $allCandidates, $childrenCount);
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

// !! написать сортировку которая берёт соответствие ТОЧКА => РАССТОЯНИЕ ДО ПОСЛЕДНЕЙ ТОЧКИ В ПУТИ
// сортирует по этому расстоянию
// и возвращает Н ближайших точек
    protected function _getNMinArrayElements($arr, $n)
    {
        $keys  = array_keys($arr);
        $point = $arr[$keys[$i]];

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
    }

//    protected function _getNMinArrayElements($arr, $n)
//    {
//        for ($i = 0; $i < $n; ++$i)
//        {
//            $min    = null;
//            $minKey = null;
//
//            for($j = $i; $j < count($arr); ++$j)
//            {
//                if (null === $min || $arr[$j] < $min)
//                {
//                    $minKey = $j;
//                    $min    = $arr[$j];
//                }
//            }
//
//            $arr[$minKey] = $arr[$i];
//            $arr[$i]      = $min;
//        }
//
//    }
}