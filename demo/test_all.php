<?php

require_once '../vendor/autoload.php';
// require 'SolutionInfoCollector.php';

$xmlConfigFile  = __DIR__.'/config.xml';


$generateRandomData      = true; // if true, data will be randomly generated before each test
$dummyMode               = false; // in Dummy mode we don't write to DB and output file is different
$obtainFullSolution      = true; //if false, dummy solution will be obtained

$pdpPointsFile           = __DIR__.'/data/pdp_points.txt'; // has sense if $generateRandomData == false. Data is taken from file in this case
$pdpConfigFile           = __DIR__.'/data/pdp_config.ini'; // default config is taken from here and additional config is merged into it
$dummyOutputCsvFile      = 'dummy.csv';
$productionOutputCsvFile = 'result.csv';
$outputCsvFile           = $dummyMode ? $dummyOutputCsvFile : $productionOutputCsvFile;

$testSuiteComment        = '';
$pairCountToTest         = [3,4,5/*5,6,7,8*/]; // has sense if $generateRandomData == true
$repeatEachTestCount     = 5;


$genPrecises = [
// pair count => all precices to try
3 => [10,30,50],
4 => [10,30,50],
5 => [10,30,50],
6 => [10,30,50]
];

$checkTransitionalLoadingProbabilites = array(0,10,20,30,40,50,60,70);

$allLoadParams = [
  ['weight_capacity' => 100, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 200, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 300, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 400, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 500, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 600, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],

  ['weight_capacity' => 100, 'load_area' => ['x' => 75, 'y' => 75, 'z' => 75]],
  ['weight_capacity' => 200, 'load_area' => ['x' => 75, 'y' => 75, 'z' => 75]],
  ['weight_capacity' => 300, 'load_area' => ['x' => 75, 'y' => 75, 'z' => 75]],
  ['weight_capacity' => 400, 'load_area' => ['x' => 75, 'y' => 75, 'z' => 75]],
  ['weight_capacity' => 500, 'load_area' => ['x' => 75, 'y' => 75, 'z' => 75]],
  ['weight_capacity' => 600, 'load_area' => ['x' => 75, 'y' => 75, 'z' => 75]],

  ['weight_capacity' => 100, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  ['weight_capacity' => 200, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  ['weight_capacity' => 300, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  ['weight_capacity' => 400, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  ['weight_capacity' => 500, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  ['weight_capacity' => 600, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],

  ['weight_capacity' => 100, 'load_area' => ['x' => 125, 'y' => 125, 'z' => 125]],
  ['weight_capacity' => 200, 'load_area' => ['x' => 125, 'y' => 125, 'z' => 125]],
  ['weight_capacity' => 300, 'load_area' => ['x' => 125, 'y' => 125, 'z' => 125]],
  ['weight_capacity' => 400, 'load_area' => ['x' => 125, 'y' => 125, 'z' => 125]],
  ['weight_capacity' => 500, 'load_area' => ['x' => 125, 'y' => 125, 'z' => 125]],
  ['weight_capacity' => 600, 'load_area' => ['x' => 125, 'y' => 125, 'z' => 125]],


  ['weight_capacity' => 100, 'load_area' => ['x' => 150, 'y' => 150, 'z' => 150]],
  ['weight_capacity' => 200, 'load_area' => ['x' => 150, 'y' => 150, 'z' => 150]],
  ['weight_capacity' => 300, 'load_area' => ['x' => 150, 'y' => 150, 'z' => 150]],
  ['weight_capacity' => 400, 'load_area' => ['x' => 150, 'y' => 150, 'z' => 150]],
  ['weight_capacity' => 500, 'load_area' => ['x' => 150, 'y' => 150, 'z' => 150]],
  ['weight_capacity' => 600, 'load_area' => ['x' => 150, 'y' => 150, 'z' => 150]],
];

\Litvinenko\Common\App::init($xmlConfigFile);

$launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;

// config is taken from this file is merged with specially set params
$configFromFile = \Litvinenko\Combinatorics\Pdp\IO::readConfigFromIniFile($pdpConfigFile);

// if we don't generate data, we take it from this file
$dataFromFile = $launcher->readPdpDataFromFile($pdpPointsFile);

function db()
{
  global $dbh;
  $dbh = null;
  try {$dbh = new PDO('mysql:host=localhost;dbname=pdp;port=3306', 'root', 'aDmiN8910');} catch (PDOException $e) {print "Error!: " . $e->getMessage() . "<br/>"; die(); }
  return $dbh;
}

function query($sql)
{
  if (!db()->query($sql))
  {
    echo "\nError in query:\n $sql\n";
    print_r(db()->errorInfo());
    die();
  }

}

$params = str_replace("'","",var_export(compact('generateRandomData', 'dummyMode', 'pairCountToTest', 'repeatEachTestCount', 'genPrecises', 'checkTransitionalLoadingProbabilites','allLoadParams'), true));
if (!$dummyMode) query("INSERT INTO test_suites(start_time,params,comment) VALUES (NOW(),'$params','$testSuiteComment')");

// file_put_contents($outputCsvFile, "sep =,\n");
if (!$generateRandomData)
{
  $pairCountToTest = [count($dataFromFile['points'])/2];
}

$dataId = 1;
foreach ($pairCountToTest as $pairCount)
{
  file_put_contents($outputCsvFile, "\n\n--------- {$pairCount} pairs --------\n", FILE_APPEND);
  foreach ($allLoadParams as $loadParams)
  {
      # code...
    // if (($pairCount == 5) && $loadParams['load_area']['x'] < 70 ) continue; //temp hardcode
    // if (($pairCount == 5) && ($loadParams['load_area']['x'] < 70)) continue; //temp hardcode
    // if (($pairCount == 5) && ($loadParams['load_area']['x'] == 70) && $loadParams['weight_capacity'] < 200 ) continue; //temp hardcode

    file_put_contents($outputCsvFile, "\n Load area " . implode(' x ', $loadParams['load_area']) . ", weight capacity {$loadParams['weight_capacity']}\n", FILE_APPEND);

    $newLine = "pair count,test#,cost,solution_time,exec_time,total_branchings,path,errors,precise,cost,solution_time,exec_time,total_generated_paths,cost_increase,path,errors,data,pdp_points.txt\n";
    file_put_contents($outputCsvFile, $newLine, FILE_APPEND);

    $testCount = isset($loadParams['test_count']) ? $loadParams['test_count'] : $repeatEachTestCount;
    for ($testNum = 1; $testNum <= $testCount; $testNum++)
    {
      unset($launcher);
      $launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;

      $data = ($generateRandomData) ? $launcher->generateRandomData($pairCount) : $dataFromFile;
      if ($generateRandomData) $dataId++;

      $pdpPointsPrepared = 'deprecated'/*file_get_contents('pdp_points.txt')*/;
      $dataPrepared      = json_encode($data);

      $exact_solution_info = "$pairCount,$testNum,";
      $check_transitional_loading_probability = 0; // for FULL solution we don't check transitional loading (it has no sense)

      if ($obtainFullSolution) if (!$dummyMode) query("INSERT INTO tests(test_suite_id,pair_count,load_area_size,weight_capacity,check_transitional_loading_probability,precise,start_time,data_id,data) VALUES ((select max(id) from test_suites),$pairCount,{$loadParams['load_area']['x']},{$loadParams['weight_capacity']},$check_transitional_loading_probability, 100, NOW(), $dataId, '$dataPrepared')");

      $genSolution = ($obtainFullSolution)
                        ? $launcher->getSolution($data, array_merge($configFromFile,['check_transitional_loading_probability' => $check_transitional_loading_probability, 'precise' => 100, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]))
                        : $launcher->getDummySolution();

      $genSolution['errors'] = ['bb method crashed. Gen method with v=100% was launched'];
      $exact_solution_info .=  "{$genSolution['path_cost']},{$genSolution['solution_time']}, deprecated,{$genSolution['info']['total_generated_paths']},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\",";
      $exactSolution = $genSolution;

      // solve with GEN method with different precises
      $prefix  = $exact_solution_info;
      $postfix = ",\"" . $dataPrepared . "\",\"" . $pdpPointsPrepared . "\"";

      if ($obtainFullSolution) if (!$dummyMode) query("INSERT INTO results(test_id,time,cost,cost_increase,path) VALUES ((select max(id) from tests),{$exactSolution['solution_time']},{$exactSolution['path_cost']},0,'".implode(' ',$exactSolution['path'])."')");

      foreach ($checkTransitionalLoadingProbabilites as $check_transitional_loading_probability)
      {
        file_put_contents($outputCsvFile, "\n\n--------- {$check_transitional_loading_probability}%  --------\n\n", FILE_APPEND);
        foreach($genPrecises[$pairCount] as $precise)
        {
          if (!$dummyMode) query("INSERT INTO tests(test_suite_id,pair_count,load_area_size,weight_capacity,check_transitional_loading_probability,precise,start_time,data_id,data) VALUES ((select max(id) from test_suites),$pairCount,{$loadParams['load_area']['x']},{$loadParams['weight_capacity']},$check_transitional_loading_probability, $precise, NOW(), $dataId, '$dataPrepared')");

          $genSolution = $launcher->getSolution($data, array_merge($configFromFile,[ 'check_transitional_loading_probability' => $check_transitional_loading_probability, 'precise' => $precise, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]));
          $costIncrease = (floatval($exactSolution['path_cost']) > 0) ? ($genSolution['path_cost']-$exactSolution['path_cost'])/$exactSolution['path_cost'] : '0';
          $newLine = $prefix . "{$precise}, {$genSolution['path_cost']},{$genSolution['solution_time']}, deprecated,{$genSolution['info']['total_generated_paths']},{$costIncrease},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\"" . $postfix . "\n";

          $prefix  = preg_replace("/[^,]+/", "", $prefix);
          $postfix = preg_replace("/[^,]+/", "", $postfix);

          file_put_contents($outputCsvFile, $newLine, FILE_APPEND);

          if (!$dummyMode) query("INSERT INTO results(test_id,time,cost,cost_increase,path) VALUES ((select max(id) from tests),{$genSolution['solution_time']},{$genSolution['path_cost']},$costIncrease,'".implode(' ',$genSolution['path'])."')");
        }
      }
    }
  }
}
if (!$dummyMode) query("set @id = (select max(id) from test_suites); UPDATE test_suites SET end_time=NOW() WHERE id=@id");

/* SQL to view results:
select s.id as suite_id,s.start_time,s.end_time,t.id as test_id,t.pair_count,t.load_area_size,t.weight_capacity,t.check_transitional_loading_probability as check_prob, t.precise,t.start_time,r.time,r.cost,r.cost_increase,t.data_id from test_suites s
inner join tests t on s.id=t.test_suite_id
left join results r on t.id=r.test_id
where s.id = (select max(id) from test_suites)
#order by test_id desc
order by s.id,t.id,t.pair_count,t.load_area_size,t.weight_capacity,t.check_transitional_loading_probability,t.precise

select s.id as suite_id,s.start_time,s.end_time,t.id as test_id,t.pair_count,t.load_area_size,t.weight_capacity,t.check_transitional_loading_probability as check_prob, t.precise,t.start_time,r.time,r.cost,r.cost_increase,t.data,t.pdp_points_txt from test_suites s
inner join tests t on s.id=t.test_suite_id
left join results r on t.id=r.test_id
where s.id = (select max(id) from test_suites)
and precise=5
and pair_count=9
order by data,t.check_transitional_loading_probability,s.id,t.id,t.pair_count,t.load_area_size,t.weight_capacity,t.precise
*/

/* db dump:*/
// -- --------------------------------------------------------
// -- Host:                         46.101.195.105
// -- Server version:               5.5.44-0ubuntu0.14.04.1 - (Ubuntu)
// -- Server OS:                    debian-linux-gnu
// -- HeidiSQL Version:             8.3.0.4705
// -- --------------------------------------------------------

// /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
// /*!40101 SET NAMES utf8 */;
// /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
// /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

// -- Dumping database structure for pdp
// CREATE DATABASE IF NOT EXISTS `pdp` /*!40100 DEFAULT CHARACTER SET latin1 */;
// USE `pdp`;


// -- Dumping structure for table pdp.results
// CREATE TABLE IF NOT EXISTS `results` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `test_id` int(11) DEFAULT NULL,
//   `time` float NOT NULL DEFAULT '0',
//   `cost` float NOT NULL DEFAULT '0',
//   `cost_increase` float NOT NULL DEFAULT '0',
//   `path` varchar(50) NOT NULL DEFAULT '0',
//   PRIMARY KEY (`id`),
//   KEY `FK__tests` (`test_id`),
//   CONSTRAINT `FK__tests` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// -- Data exporting was unselected.


// -- Dumping structure for table pdp.tests
// CREATE TABLE IF NOT EXISTS `tests` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `test_suite_id` int(11) NOT NULL,
//   `pair_count` int(11) NOT NULL,
//   `load_area_size` int(11) NOT NULL,
//   `weight_capacity` int(11) NOT NULL,
//   `check_transitional_loading_probability` int(11) DEFAULT NULL,
//   `precise` tinyint(4) DEFAULT NULL,
//   `start_time` datetime DEFAULT NULL,
//   `data` text,
//   `pdp_points_txt` text,
//   PRIMARY KEY (`id`),
//   KEY `FK_tests_test_suites` (`test_suite_id`),
//   CONSTRAINT `FK_tests_test_suites` FOREIGN KEY (`test_suite_id`) REFERENCES `test_suites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
// ALTER TABLE `tests`
  // CHANGE COLUMN `data` `data` VARCHAR(2000) NULL AFTER `start_time`,
  // ADD INDEX `data` (`data`);
// -- Data exporting was unselected.
//
// ALTER TABLE `tests`
//   ADD COLUMN `data_id` INT NULL AFTER `pdp_points_txt`;
//
// ALTER TABLE `tests`
//   DROP COLUMN `pdp_points_txt`;


// -- Dumping structure for table pdp.test_suites
// CREATE TABLE IF NOT EXISTS `test_suites` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
//   `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
//   `params` longtext CHARACTER SET utf8 NOT NULL,
//   `comment` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

// -- Data exporting was unselected.
// /*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
// /*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
// /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
