<?php
include '../vendor/autoload.php';
include 'PdpLauncher.php';

$launcher = new PdpLauncher;

$pairCountToTest     = [2];
$repeatEachTestNmber = 2;

$genPrecises = [
// pair count => all precices to try
2 => [25,50,100],
3 => [25,50,100],
4 => [40,60,80],
5 => [30,40,50]
];

$result = "sep =,\npair count,test#,cost,time,total_branchings,path,errors,precise,cost,time,total_generated_paths,path,errors,data\n";
foreach ($pairCountToTest as $pairCount)
{
  for ($testNum = 1; $testNum <= $repeatEachTestNmber; $testNum++)
  {
    $data = $launcher->generateRandomData($pairCount);
    $bbSolution  = $launcher->getSolution('branch_bound', $data);


    $begin_and_branch_bound_info = "$pairCount,$testNum,";
    $begin_and_branch_bound_info .= "{$bbSolution['path_cost']}, {$bbSolution['solution_time']}, {$bbSolution['info']['total_branchings']},\"" . implode(' ', $bbSolution['path']) . "\",\"" . (isset($bbSolution['errors']) ? implode(';',$bbSolution['errors']) : '') ."\",";

    // solve with GEN method with different precises
    foreach($genPrecises[$pairCount] as $precise)
    {
      $genSolution = $launcher->getSolution('gen', $data, ['precise' => $precise]);

      $result .= $begin_and_branch_bound_info. "{precise}, {$genSolution['path_cost']},{$genSolution['solution_time']},{$genSolution['info']['total_generated_paths']},\"" . implode(' ', $genSolution['path']) . "\",\"" . (isset($genSolution['errors']) ? implode(';',$genSolution['errors']) : '') ."\"";
      $result .= ",\"" . json_encode($data) . "\"\n";
    }


  }
}

file_put_contents('result.csv', $result);