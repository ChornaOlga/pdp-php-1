<?php
namespace Pdp;

class Point
{
    const TYPE_PICKUP   = 'pickup';
    const TYPE_DELIVERY = 'delivery';

    const TYPE_DEPOT = 'depot';
    const DEPOT_ID   = 0;

    protected $type;
    protected $id;

    public $x, $y;
    public $q;

    public function __construct($id, $x, $y, $type, $q = null)
    {
        $this->id   = $id;
        $this->x    = (float)$x;
        $this->y    = (float)$y;
        $this->type = $type;
        $this->q    = (float)$q;
    }
}