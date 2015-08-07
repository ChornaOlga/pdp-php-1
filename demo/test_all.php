<?php
require_once '../vendor/autoload.php';
include 'PdpLauncher.php';

$generateRandomData      = true;
$dummyMode               = true;

$dummyOutputCsvFile      = 'dummy.csv';
$productionOutputCsvFile = 'result.csv';
$outputCsvFile           = $dummyMode ? $dummyOutputCsvFile : $productionOutputCsvFile;

$pairCountToTest         = [/*3,4,*/5,6,7,8];
$repeatEachTestCount     = 5;

$pairCountToTest         = [2/*5,6,7*/];
$repeatEachTestCount     = 5;

$genPrecises = [
// pair count => all precices to try
2 => [25,50/*,100*/],
3 => [20/*,40,60,80*//*,100*/],
4 => [5,10,20,40,60,80,100],
5 => [5,10,20,40,60/*,100*/],
6 => [5,10,20,40,60/*,100*/],7 => [5,10,20,30,100],8 => [5,10,20,30,100],

];

$allLoadParams = [
//   // ['weight_capacity' => 100,  'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
//   // ['weight_capacity' => 150,  'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 300, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
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


try {$dbh = new PDO('mysql:host=localhost;dbname=pdp', 'root', '');} catch (PDOException $e) {print "Error!: " . $e->getMessage() . "<br/>"; die(); }

$launcher = new PdpLauncher;
$params = str_replace("'","",var_export(compact('generateRandomData', 'dummyMode', 'pairCountToTest', 'repeatEachTestCount', 'genPrecises', 'allLoadParams'), true));
if (!$dbh->query("INSERT INTO test_suites(start_time,params) VALUES (NOW(),'$params')")) print_r($dbh->errorInfo());

// file_put_contents($outputCsvFile, "sep =,\n");
foreach ($pairCountToTest as $pairCount)
{
  file_put_contents($outputCsvFile, "\n\n--------- {$pairCount} pairs --------\n", FILE_APPEND);
  foreach ($allLoadParams as $loadParams)
  {
    // if (($pairCount == 4) && $loadParams['load_area']['x'] < 70 ) continue; //temp hardcode
    // if (($pairCount == 4) && ($loadParams['load_area']['x'] == 70) && $loadParams['weight_capacity'] == 100 ) continue; //temp hardcode

    file_put_contents($outputCsvFile, "\n Load area " . implode(' x ', $loadParams['load_area']) . ", weight capacity {$loadParams['weight_capacity']}\n", FILE_APPEND);

    $newLine = "pair count,test#,cost,solution_time,exec_time,total_branchings,path,errors,precise,cost,solution_time,exec_time,total_generated_paths,cost_increase,path,errors,data,pdp_points.txt\n";
    file_put_contents($outputCsvFile, $newLine, FILE_APPEND);

    $testCount = isset($loadParams['test_count']) ? $loadParams['test_count'] : $repeatEachTestCount;
    for ($testNum = 1; $testNum <= $testCount; $testNum++)
    {
      unset($launcher);
      $launcher = new PdpLauncher;

      $data = ($generateRandomData) ? $launcher->generateRandomData($pairCount) : [];

      $pdpPointsPrepared = str_replace(PHP_EOL, "!", file_get_contents('pdp_points.txt'));
      $dataPrepared      = json_encode($data);

      if (!$dbh->query("INSERT INTO tests(test_suite_id,pair_count,load_area_size,weight_capacity,data,pdp_points_txt) VALUES ((select max(id) from test_suites),$pairCount,{$loadParams['load_area']['x']},{$loadParams['weight_capacity']}, '$dataPrepared', '$pdpPointsPrepared')")) print_r($dbh->errorInfo());

      $exact_solution_info = "$pairCount,$testNum,";
      // $bbSolution = $launcher->getSolution('branch_bound', $data, ['weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]);

      // if branch_bound method crashed, launch gen method with v=100%;
      if (!isset($bbSolution) || empty($bbSolution['path_cost']))
      {
          $genSolution = $launcher->getSolution('gen', $data, ['precise' => 100, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]);
          $genSolution['errors'] = ['bb method crashed. Gen method with v=100% was launched'];
          $exact_solution_info .=  "{$genSolution['path_cost']},{$genSolution['solution_time']}, {$genSolution['exec_time']},{$genSolution['info']['total_generated_paths']},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\",";
          $exactSolution = $genSolution;
      }
      else
      {
        $exact_solution_info .= "{$bbSolution['path_cost']}, {$bbSolution['solution_time']}, {$bbSolution['exec_time']}, {$bbSolution['info']['total_branchings']},\"" . (isset($bbSolution['path']) ? implode(' ',$bbSolution['path']) : '-') . "\",\"" . (isset($bbSolution['errors']) ? implode(';',$bbSolution['errors']) : '') ."\",";
        $exactSolution = $bbSolution;
      }

      // solve with GEN method with different precises
      $prefix  = $exact_solution_info;
      $postfix = ",\"" . $dataPrepared . "\",\"" . $pdpPointsPrepared . "\"";

      if (!$dbh->query("INSERT INTO results(test_id,time,cost,cost_increase,path) VALUES ((select max(id) from tests),{$exactSolution['solution_time']},{$exactSolution['path_cost']},0,'".implode(' ',$exactSolution['path'])."')")) print_r($dbh->errorInfo());

      foreach($genPrecises[$pairCount] as $precise)
      {
        $genSolution = $launcher->getSolution('gen', $data, [ 'precise' => $precise, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]);

        $costIncrease = (floatval($exactSolution['path_cost']) > 0) ? ($genSolution['path_cost']-$exactSolution['path_cost'])/$exactSolution['path_cost'] : '';
        $newLine = $prefix . "{$precise}, {$genSolution['path_cost']},{$genSolution['solution_time']}, {$genSolution['exec_time']},{$genSolution['info']['total_generated_paths']},{$costIncrease},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\"" . $postfix . "\n";

        $prefix  = preg_replace("/[^,]+/", "", $prefix);
        $postfix = preg_replace("/[^,]+/", "", $postfix);

        file_put_contents($outputCsvFile, $newLine, FILE_APPEND);

        if (!$dbh->query("INSERT INTO results(test_id,time,cost,cost_increase,path) VALUES ((select max(id) from tests),{$genSolution['solution_time']},{$genSolution['path_cost']},$costIncrease,'".implode(' ',$genSolution['path'])."')")) print_r($dbh->errorInfo());
      }
    }
  }
}

if (!$dbh->query("set @id = (select max(id) from test_suites); UPDATE test_suites SET end_time=NOW() WHERE id=@id")) print_r($dbh->errorInfo());
$dbh = null;