<?php
namespace Litvinenko\Combinatorics\Pdp\Generators\Recursive;

class PrecisedPdpPermutationGenerator extends PdpPermutationGenerator
{
    protected $dataRules = array(
        'generating_elements' => 'required|array',
        'tuple_length'        => 'required|integer_strict',
        'initial_object'      => 'not_null|array',

        // PDP specific params
        'current_path'    => 'required|object:\Litvinenko\Combinatorics\Pdp\Path', // current PDP path
        'weight_capacity' => 'required|float_strict',                              // vehicle weight capacity

        // this class specific params
        'precise' => 'required|float_strict'
    );

    protected function _getSuccessiveElements($tuple)
    {
        $result = [];

        if ($allPossiblePoints = parent::_getSuccessiveElements($tuple))
        {
            $result = $this->_getNMinArrayElements($allPossiblePoints, $this->getPrecise() * count($allPossiblePoints));
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

// !! написать сортировку которая берёт соответствие ТОЧКА => РАССТОЯНИЕ ДО ПОСЛЕДНЕЙ ТОЧКИ В ПУТИ
// сортирует по этому расстоянию
// и возвращает Н ближайших точек
    protected function _getNMinArrayElements($arr, $n)
    {
        for ($i = 0; $i < $n; ++$i)
        {
            $min    = null;
            $minKey = null;

            for($j = $i; $j < count($arr); ++$j)
            {
                if (null === $min || $arr[$j] < $min)
                {
                    $minKey = $j;
                    $min    = $arr[$j];
                }
            }

            $arr[$minKey] = $arr[$i];
            $arr[$i]      = $min;
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