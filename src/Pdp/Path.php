<?php

namespace Litvinenko\Combinatorics\Pdp;
class Path extends \Litvinenko\Common\Object
{
    protected $dataRules = array(
        'points' => 'required|array'
    );

    function _construct()
    {
        $this->setPoints([]);
    }

    public function getCost()
    {
        $result = 0;
        $points = $this->getPoints();
        $keys = array_keys($points);

        // iterate throw points and sum distances (assuming that $points is array with numeric or NON-numeric keys)
        for ($i = 0; $i < (count($points) - 1); $i++)
        {
            $point     = $points[$keys[$i]];
            $nextPoint = $points[$keys[$i+1]];

            $result   += $point->getDistanceTo($nextPoint);
        }
    }

    public function getPointsCount()
    {
        $points = $this->getPoints();
        return (is_array($points)) ? count($points) : 0;
    }

    // public function addItem($obj, $key = null) {
    // }

    // public function deleteItem($key) {
    // }

    // public function getItem($key) {
    // }
}
