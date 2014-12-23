<?php
include 'vendor/autoload.php';
$builder = new Tree\Builder\NodeBuilder;

$builder
    ->value('A')
    ->leaf('B')
    ->tree('C')
        ->tree('D')
            ->leaf('G')
            ->leaf('H')
            ->end()
        ->leaf('E')
        ->leaf('F')
        ->end()
;

$nodeA = $builder->getNode();