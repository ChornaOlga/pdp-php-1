<?php
require 'vendor/autoload.php';

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

    // public function __construct($id, $x, $y, $type, $q = null)
    // {
    //     $this->id   = $id;
    //     $this->x    = (float)$x;
    //     $this->y    = (float)$y;
    //     $this->type = $type;
    //     $this->q    = (float)$q;
    // }

    // function __call($method, $params)
    // {
    //     $var = strtolower(substr($method, 3));

    //     if (strncasecmp($method, "get", 3))
    //     {
    //         return $this->$var;
    //     }
    // }
}

$a = new Point;
$a->setData([
    'x' => 123]);
var_dump($a->getX());
var_dump($a->getOlolo());
// require 'vendor/autoload.php';
// $c = new Doctrine\Common\Collections\ArrayCollection;
// $c->add(123);
// $c->add(456);

// var_dump($c->getValues());
// $a=new StdClass;
// $a->name='a_name';

// $b=new StdClass;
// $b->name='b_name';

// $ar = [&$a, $b];
// var_dump($ar);

// unset($ar[0]);
// var_dump($a);