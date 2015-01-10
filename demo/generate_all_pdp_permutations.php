<?php
// generates and outputs all PDP permutations.
// PDP points are taken from text file

use \Litvinenko\Combinatorics\Pdp\IO;
use \Litvinenko\Combinatorics\Pdp\Generators\Recursive\PdpPermutationGenerator as Generator;

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

$points = IO::readFromFile($pdpInfoFile)->getPoints();

$generator = new Generator([
    'tuple_length'        => count($points),
    'generating_elements' => _getGeneratorDataFromPoints($points)
    ]);

echo "<pre>\n";
foreach ($generator->generateAll() as $permutation)
{
    echo  implode(' ', array_column($permutation, 'combinatorial_value')) . "\n";
}
