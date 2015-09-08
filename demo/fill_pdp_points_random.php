<?php
// use script like this:
// php fill_pdp_points_random.php PAIR_COUNT [OUTPUT_FILENAME]

include '../vendor/autoload.php';
// include 'PdpLauncher.php';

$launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;
$filename = empty($argv[2]) ? __DIR__.'/data/pdp_points.txt' : $argv[2];
$data     = $launcher->generateRandomData($argv[1]);
$launcher->writePdpDataToFile($data, $filename);