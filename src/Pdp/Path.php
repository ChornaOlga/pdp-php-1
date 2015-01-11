<?php

namespace Litvinenko\Combinatorics\Pdp;
class Path extends \Litvinenko\Common\Object
{
    protected $dataRules = array(
        'points' => 'required|array'
    );

    function _construct()
    {
        if (!$this->hasPoints()) $this->setPoints([]);
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

        return $result;
    }

    public function getPointsCount()
    {
        $points = $this->getPoints();
        return (is_array($points)) ? count($points) : 0;
    }

    /**
     * Returns array of ids of path points
     *
     * @return array
     */
    public function getPointIds()
    {
        $result = [];

        foreach ($this->getPoints() as $point)
        {
            $result[] = $point->getId();
        }

        return $result;
    }

    /**
     * Returns array of ids of path points
     *
     * @return array
     */
    public function getCurrentWeight()
    {
        $result = 0;

        foreach ($this->getPoints() as $point)
        {
            $result += $point->getBoxWeight();
        }

        return $result;
    }

    /**
     * Returns TRUE if path contains given point (or point withh given ID)
     *
     * @param  int|\Litvinenko\Combinatorics\Pdp\Point $point point object or just point ID
     *
     * @return bool
     */
    public function doesContain($point)
    {
        if ($point instanceof Point)
        {
            $pointId = $point->getId();
        }
        elseif (is_integer($point))
        {
            $pointId = $point;
        }
        else
        {
            throw new \Exception("given point is not integer or Pdp\\Point object");
        }

        $result = false;
        foreach ($this->getPoints() as $point)
        {
            if ($point->getId() == $pointId)
            {
                $result = true;
                break;
            }
        }

        return $result;
    }

    // public function addItem($obj, $key = null) {
    // }

    // public function deleteItem($key) {
    // }

    // public function getItem($key) {
    // }
}
