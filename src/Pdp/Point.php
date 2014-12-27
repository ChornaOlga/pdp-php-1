<?php
namespace Litvinenko\Combinatorics\Pdp;

class Point extends \Varien_Object
{
    const TYPE_PICKUP   = 'pickup';
    const TYPE_DELIVERY = 'delivery';

    const TYPE_DEPOT = 'depot';
    const DEPOT_ID   = 0;

    // protected $type;
    // protected $id;

    // public $x, $y;
    // public $q;

    public function getDistanceTo($point)
    {
        return sqrt(pow($this->getX() - $point->getX(),2) + pow($this->getY() - $point->getY(),2));
    }
}