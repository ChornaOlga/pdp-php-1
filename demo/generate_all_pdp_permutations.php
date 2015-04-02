<?php
// generates and outputs all PDP permutations.
// PDP points are taken from text file

use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Combinatorics\Pdp\Generators\Recursive\PdpPermutationGenerator as Generator;
use Litvinenko\Combinatorics\Pdp\Path;

require_once '../vendor/autoload.php';

function _getGeneratorDataFromPoints($points)
{
    $result = [];
    foreach ($points as $point)
    {
        $result[] = [
        'id'                  => $point->getId(),
        'value'               => $point,
        'combinatorial_value' => $point->getCombinatorialValue()
        ];
    }
    return $result;
}

$pdpInfoFile = 'pdp_points.txt';

$allPoints   = IO::readPointsFromFile($pdpInfoFile);
$points      = $allPoints['points'];

$generator = new Generator([
    'tuple_length'        => count($points),
    'generating_elements' => _getGeneratorDataFromPoints($points),
    'current_path'        => new Path(['points' => [$allPoints['depot']] ]),
    'initial_object'      => _getGeneratorDataFromPoints([$allPoints['depot']]),
    'weight_capacity'     => 10000,
    'load_area'           => ['x' => 10000, 'y' => 10000, 'z' => 10000],

    'enable_logs' => true
    ]);

// initial_object
// current_path

echo "<pre>\n";
foreach ($generator->generateAll() as $permutation)
{
    echo  implode(' ', array_column($permutation, 'combinatorial_value')) . "\n";
}
