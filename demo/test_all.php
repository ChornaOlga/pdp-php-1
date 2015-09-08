<?php

require_once '../vendor/autoload.php';
// require 'SolutionInfoCollector.php';

$xmlConfigFile  = __DIR__.'/config.xml';

$generateRandomData      = true;
$dummyMode               = true;

$dummyOutputCsvFile      = 'dummy.csv';
$productionOutputCsvFile = 'result.csv';
$outputCsvFile           = $dummyMode ? $dummyOutputCsvFile : $productionOutputCsvFile;

$testSuiteComment        = 'continuing test suite 8. testCount=1';
$pairCountToTest         = [2/*5,6,7,8*/];
$repeatEachTestCount     = 3;

$genPrecises = [
// pair count => all precices to try
2 => [25,50/*,100*/],
3 => [20,40,60,80],
4 => [5,20,40,60],
5 => [5,20,40,60/*,100*/],
6 => [5,20,40,60/*,100*/],7 => [5,20,30],8 => [5,20,30],

];

$allLoadParams = [
  ['weight_capacity' => 100,  'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  // ['weight_capacity' => 150,  'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]/*, 'test_count' => 4*/],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 60, 'y' => 60, 'z' => 60]],
  // ['weight_capacity' => 150, 'load_area' => ['x' => 60, 'y' => 60, 'z' => 60]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 60, 'y' => 60, 'z' => 60]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 60, 'y' => 60, 'z' => 60]],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 60, 'y' => 60, 'z' => 60]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 60, 'y' => 60, 'z' => 60]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 70, 'y' => 70, 'z' => 70]],
  // ['weight_capacity' => 150, 'load_area' => ['x' => 70, 'y' => 70, 'z' => 70]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 70, 'y' => 70, 'z' => 70]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 70, 'y' => 70, 'z' => 70]],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 70, 'y' => 70, 'z' => 70]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 70, 'y' => 70, 'z' => 70]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 80, 'y' => 80, 'z' => 80]],
  // ['weight_capacity' => 150, 'load_area' => ['x' => 80, 'y' => 80, 'z' => 80]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 80, 'y' => 80, 'z' => 80]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 80, 'y' => 80, 'z' => 80]],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 80, 'y' => 80, 'z' => 80]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 80, 'y' => 80, 'z' => 80]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],
  // ['weight_capacity' => 150, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]/*, 'test_count' => 5*/],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 150, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],

];

function db()
{
  global $dbh;
  $dbh = null;
  try {$dbh = new PDO('mysql:host=localhost;dbname=pdp;port=3307', 'root', '');} catch (PDOException $e) {print "Error!: " . $e->getMessage() . "<br/>"; die(); }
  return $dbh;
}

\Litvinenko\Common\App::init($xmlConfigFile);

$launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;

// $pairCountToTest         = [35,6,7];
// $repeatEachTestCount     = 5;


$params = str_replace("'","",var_export(compact('generateRandomData', 'dummyMode', 'pairCountToTest', 'repeatEachTestCount', 'genPrecises', 'allLoadParams'), true));
if (!$dummyMode) {if (!db()->query("INSERT INTO test_suites(start_time,params,comment) VALUES (NOW(),'$params','$testSuiteComment')")) print_r(db()->errorInfo());}

// file_put_contents($outputCsvFile, "sep =,\n");
foreach ($pairCountToTest as $pairCount)
{
  file_put_contents($outputCsvFile, "\n\n--------- {$pairCount} pairs --------\n", FILE_APPEND);
  foreach ($allLoadParams as $loadParams)
  {
    // if (($pairCount == 5) && $loadParams['load_area']['x'] < 70 ) continue; //temp hardcode
    if (($pairCount == 5) && ($loadParams['load_area']['x'] < 70)) continue; //temp hardcode
    if (($pairCount == 5) && ($loadParams['load_area']['x'] == 70) && $loadParams['weight_capacity'] < 200 ) continue; //temp hardcode

    file_put_contents($outputCsvFile, "\n Load area " . implode(' x ', $loadParams['load_area']) . ", weight capacity {$loadParams['weight_capacity']}\n", FILE_APPEND);

    $newLine = "pair count,test#,cost,solution_time,exec_time,total_branchings,path,errors,precise,cost,solution_time,exec_time,total_generated_paths,cost_increase,path,errors,data,pdp_points.txt\n";
    file_put_contents($outputCsvFile, $newLine, FILE_APPEND);

    $testCount = isset($loadParams['test_count']) ? $loadParams['test_count'] : $repeatEachTestCount;
    for ($testNum = 1; $testNum <= $testCount; $testNum++)
    {
      unset($launcher);
      $launcher = new \Litvinenko\Combinatorics\Pdp\Pdp;

      $data = ($generateRandomData) ? $launcher->generateRandomData($pairCount) : [];

      $pdpPointsPrepared = 'deprecated'/*file_get_contents('pdp_points.txt')*/;
      $dataPrepared      = json_encode($data);

      if (!$dummyMode) {if (!db()->query("INSERT INTO tests(test_suite_id,pair_count,load_area_size,weight_capacity,data,pdp_points_txt) VALUES ((select max(id) from test_suites),$pairCount,{$loadParams['load_area']['x']},{$loadParams['weight_capacity']}, '$dataPrepared', '$pdpPointsPrepared')")) print_r(db()->errorInfo());}

      $exact_solution_info = "$pairCount,$testNum,";

      $genSolution = $launcher->getSolution($data, ['precise' => 100, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]);
      $genSolution['errors'] = ['bb method crashed. Gen method with v=100% was launched'];
      $exact_solution_info .=  "{$genSolution['path_cost']},{$genSolution['solution_time']}, deprecated,{$genSolution['info']['total_generated_paths']},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\",";
      $exactSolution = $genSolution;

      // solve with GEN method with different precises
      $prefix  = $exact_solution_info;
      $postfix = ",\"" . $dataPrepared . "\",\"" . $pdpPointsPrepared . "\"";

      foreach($genPrecises[$pairCount] as $precise)
      {
        $genSolution = $launcher->getSolution($data, [ 'precise' => $precise, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]);

        $costIncrease = (floatval($exactSolution['path_cost']) > 0) ? ($genSolution['path_cost']-$exactSolution['path_cost'])/$exactSolution['path_cost'] : '';
        $newLine = $prefix . "{$precise}, {$genSolution['path_cost']},{$genSolution['solution_time']}, deprecated,{$genSolution['info']['total_generated_paths']},{$costIncrease},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\"" . $postfix . "\n";

        $prefix  = preg_replace("/[^,]+/", "", $prefix);
        $postfix = preg_replace("/[^,]+/", "", $postfix);

        file_put_contents($outputCsvFile, $newLine, FILE_APPEND);

        if (!$dummyMode) {if (!db()->query("INSERT INTO results(test_id,start_time,time,precise,cost,cost_increase,path) VALUES ((select max(id) from tests),NOW(),{$genSolution['solution_time']},$precise,{$genSolution['path_cost']},$costIncrease,'".implode(' ',$genSolution['path'])."')")) print_r(db()->errorInfo());}
      }

      if (!$dummyMode) {if (!db()->query("INSERT INTO results(test_id,start_time,time,precise,cost,cost_increase,path) VALUES ((select max(id) from tests),NOW(),{$exactSolution['solution_time']},100,{$exactSolution['path_cost']},0,'".implode(' ',$exactSolution['path'])."')")) print_r(db()->errorInfo());}
    }
  }
}

if (!$dummyMode) {if (!db()->query("set @id = (select max(id) from test_suites); UPDATE test_suites SET end_time=NOW() WHERE id=@id")) print_r(db()->errorInfo());}

/* SQL to view results:
select s.id as suite_id,s.start_time,s.end_time,t.id as test_id,t.pair_count,t.load_area_size,t.weight_capacity,r.start_time,r.time,r.cost,r.precise,r.cost_increase,t.data,t.pdp_points_txt from test_suites s, tests t, results r
where s.id=t.test_suite_id and t.id=r.test_id
and s.id = (select max(id) from test_suites)
order by s.id,t.pair_count,t.load_area_size,t.weight_capacity
*/

/* db dump:
-- --------------------------------------------------------
--
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

// CREATE DATABASE IF NOT EXISTS `pdp` /*!40100 DEFAULT CHARACTER SET latin1 */;
// USE `pdp`;


// CREATE TABLE IF NOT EXISTS `results` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `test_id` int(11) DEFAULT NULL,
//   `time` float NOT NULL DEFAULT '0',
//   `start_time` DATETIME NULL DEFAULT NULL,
//   `cost` float NOT NULL DEFAULT '0',
//   `precise` tinyint(4) NOT NULL DEFAULT '0',
//   `cost_increase` float NOT NULL DEFAULT '0',
//   `path` varchar(50) NOT NULL DEFAULT '0',
//   PRIMARY KEY (`id`),
//   KEY `FK__tests` (`test_id`),
//   CONSTRAINT `FK__tests` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;



// CREATE TABLE IF NOT EXISTS `tests` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `test_suite_id` int(11) NOT NULL,
//   `pair_count` int(11) NOT NULL,
//   `load_area_size` int(11) NOT NULL,
//   `weight_capacity` int(11) NOT NULL,
//   `data` text,
//   `pdp_points_txt` text,
//   PRIMARY KEY (`id`),
//   KEY `FK_tests_test_suites` (`test_suite_id`),
//   CONSTRAINT `FK_tests_test_suites` FOREIGN KEY (`test_suite_id`) REFERENCES `test_suites` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;



// CREATE TABLE IF NOT EXISTS `test_suites` (
//   `id` int(11) NOT NULL AUTO_INCREMENT,
//   `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
//   `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
//   `params` mediumtext NOT NULL,
//   `comment` VARCHAR(500) NULL DEFAULT NULL,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// /*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
// /*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
// /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;