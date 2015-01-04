<?php
namespace Litvinenko\Combinatorics\Pdp;

class Point extends \Litvinenko\Common\Object
{
    const TYPE_PICKUP   = 'pickup';
    const TYPE_DELIVERY = 'delivery';
    const TYPE_DEPOT    = 'depot';
    const DEPOT_ID      = 0;

    public function _construct()
    {
        $this->dataRules = array(
            'id'      => 'required',
            'type'    => 'required|in:' . self::TYPE_PICKUP . ',' . self::TYPE_DELIVERY . ',' . self::TYPE_DEPOT,
            'x'       => 'required|float_strict',
            'y'       => 'required|float_strict',
        );
    }

    public function getDistanceTo($point)
    {
        return sqrt(pow($this->getX() - $point->getX(),2) + pow($this->getY() - $point->getY(),2));
    }

    /**
     * Returns array of ids of given points
     *
     * @param  array $points
     *
     * @return array
     */
    public static function getPointIds($points)
    {
        $result = [];
        if (is_array($points))
        {
            foreach ($points as $point)
            {
                $result[] = $point->getId();
            }
        }

        return $result;
    }

    public static function getPointCount($points)
    {
        return (is_array($points)) ? count($points) : 0;
    }

    public function getCombinatorialValue()
    {
        return (int)$this->getId();
    }
}