<?php
// use script like this:
// php fill_pdp_points_random.php PAIR_COUNT [OUTPUT_FILENAME]

include '../vendor/autoload.php';
include 'PdpLauncher.php';

$launcher = new PdpLauncher;
$filename = empty($argv[2]) ? 'pdp_points.txt' : $argv[2];
$data     = $launcher->generateRandomData($argv[1]);
$launcher->writePdpPointsContent($filename, $data);