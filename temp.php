<?php
require 'vendor/autoload.php';
$c = new Doctrine\Common\Collections\ArrayCollection;
$c->add(123);
$c->add(456);

var_dump($c->getValues());
// $a=new StdClass;
// $a->name='a_name';

// $b=new StdClass;
// $b->name='b_name';

// $ar = [&$a, $b];
// var_dump($ar);

// unset($ar[0]);
// var_dump($a);