<?php
require_once '../vendor/autoload.php';
include 'PdpLauncher.php';

$pairCountToTest     = [2];
$repeatEachTestNmber = 2;

$genPrecises = [
// pair count => all precices to try
2 => [25,50,100],
3 => [25,50,100],
4 => [40,60,80],
5 => [30,40,50]
];

$allLoadParams = [
['weight_capacity' => 10000, 'load_area' => ['x' => 20, 'y' => 20, 'z' => 20]],
  ['weight_capacity' => 10000, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  ['weight_capacity' => 10050, 'load_area' => ['x' => 200, 'y' => 200, 'z' => 200]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 50, 'y' => 50, 'z' => 50]],
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
  // ['weight_capacity' => 300, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 90, 'y' => 90, 'z' => 90]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 150, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 200, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 300, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 400, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],
  // ['weight_capacity' => 500, 'load_area' => ['x' => 100, 'y' => 100, 'z' => 100]],

  // ['weight_capacity' => 100, 'load_area' => ['x' => 10, 'y' => 10, 'z' => 10]],
  // ['weight_capacity' => 100, 'load_area' => ['x' => 10, 'y' => 10, 'z' => 10]],
  // ['weight_capacity' => 100, 'load_area' => ['x' => 10, 'y' => 10, 'z' => 10]],
];

$launcher = new PdpLauncher;

file_put_contents('result.csv', "sep =,\n");
foreach ($pairCountToTest as $pairCount)
{
  file_put_contents('result.csv', "\n\n--------- {$pairCount} pairs --------\n", FILE_APPEND);
  foreach ($allLoadParams as $loadParams)
  {
    file_put_contents('result.csv', "\n Load area " . implode(' x ', $loadParams['load_area']) . ", weight capacity {$loadParams['weight_capacity']}\n", FILE_APPEND);

    $newLine = "pair count,test#,cost,time,total_branchings,path,errors,precise,cost,time,total_generated_paths,path,errors,data,pdp_points.txt\n";
    file_put_contents('result.csv', $newLine, FILE_APPEND);

    for ($testNum = 1; $testNum <= $repeatEachTestNmber; $testNum++)
    {
      unset($launcher);
      $launcher = new PdpLauncher;

      $data    = $launcher->generateRandomData($pairCount);

      // $data       = json_decode();

      $bbSolution = $launcher->getSolution('branch_bound', $data);


      $begin_and_branch_bound_info = "$pairCount,$testNum,";
      $begin_and_branch_bound_info .= "{$bbSolution['path_cost']}, {$bbSolution['solution_time']}, {$bbSolution['info']['total_branchings']},\"" . (isset($bbSolution['path']) ? implode(' ',$bbSolution['path']) : '-') . "\",\"" . (isset($bbSolution['errors']) ? implode(';',$bbSolution['errors']) : '') ."\",";

      // solve with GEN method with different precises
      $prefix  = $begin_and_branch_bound_info;
      $postfix = ",\"" . json_encode($data) . "\",\"" . str_replace(PHP_EOL, "\\", file_get_contents('pdp_points.txt')) . "\"";
      foreach($genPrecises[$pairCount] as $precise)
      {
        $genSolution = $launcher->getSolution('gen', $data, [ 'precise' => $precise, 'weight_capacity' => $loadParams['weight_capacity'], 'load_area' => $loadParams['load_area'] ]);

        $newLine = $prefix . "{$precise}, {$genSolution['path_cost']},{$genSolution['solution_time']},{$genSolution['info']['total_generated_paths']},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\"" . $postfix . "\n";

        $prefix  = preg_replace("/[^,]+/", "", $prefix);
        $postfix = preg_replace("/[^,]+/", "", $postfix);

        file_put_contents('result.csv', $newLine, FILE_APPEND);
      }

    }
  }
}