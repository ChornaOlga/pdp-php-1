<?php
require_once '../vendor/autoload.php';
include 'PdpLauncher.php';

$pairCountToTest     = [3];
$repeatEachTestNmber = 1;

$genPrecises = [
// pair count => all precices to try
2 => [25,50,100],
3 => [25,50,100],
4 => [40,60,80],
5 => [30,40,50]
];

// $loadAreas = [

// ];
$launcher = new PdpLauncher;
$newLine = "sep =,\npair count,test#,cost,time,total_branchings,path,errors,precise,cost,time,total_generated_paths,path,errors,data\n";
file_put_contents('result.csv', $newLine);
foreach ($pairCountToTest as $pairCount)
{
  for ($testNum = 1; $testNum <= $repeatEachTestNmber; $testNum++)
  {
    unset($launcher);
    $launcher = new PdpLauncher;

    $data = $launcher->generateRandomData($pairCount);
    $bbSolution  = $launcher->getSolution('branch_bound', $data);


    $begin_and_branch_bound_info = "$pairCount,$testNum,";
    $begin_and_branch_bound_info .= "{$bbSolution['path_cost']}, {$bbSolution['solution_time']}, {$bbSolution['info']['total_branchings']},\"" . (isset($bbSolution['path']) ? implode(' ',$bbSolution['path']) : '-') . "\",\"" . (isset($bbSolution['errors']) ? implode(';',$bbSolution['errors']) : '') ."\",";

    // solve with GEN method with different precises
    $prefix = $begin_and_branch_bound_info;
    foreach($genPrecises[$pairCount] as $precise)
    {
      $genSolution = $launcher->getSolution('gen', $data, ['precise' => $precise]);

      $newLine = $prefix. "{$precise}, {$genSolution['path_cost']},{$genSolution['solution_time']},{$genSolution['info']['total_generated_paths']},\"" . (isset($genSolution['path']) ? implode(' ',$genSolution['path']) : '-') . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\"";
      $newLine .= ",\"" . json_encode($data) . "\"\n";

      $prefix = preg_replace("/[^,]+/", "", $prefix);

      file_put_contents('result.csv', $newLine, FILE_APPEND);
    }

  }
}
// file_put_contents('result.csv', $newLine, FILE_APPEND);
