<?php

require_once '../vendor/autoload.php';
// require 'SolutionInfoCollector.php';

$xmlConfigFile  = __DIR__.'/config.xml';
$pdpPointsFile  = __DIR__.'/data/pdp_points.txt';
$pdpConfigFile  = __DIR__.'/data/pdp_config.ini';

\Litvinenko\Common\App::init($xmlConfigFile);

$launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;
$data     = $launcher->readPdpDataFromFile($pdpPointsFile);
$config   = \Litvinenko\Combinatorics\Pdp\IO::readConfigFromIniFile($pdpConfigFile);

echo json_encode($launcher->getSolution($data, $config));

echo "\n";