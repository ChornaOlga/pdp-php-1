<?php
//ini_set('max_input_time', 300);
//phpinfo();

$worsepointsArr = [];
$worsepoint = [5, 10, 7];
$permutationorder = [[1, 2, 0],[2, 0, 1]];

$response['clusters'] = [
    [5,15,6,16],
    [2,12,3,13,4,14,8,18,10,20],
    [1,11,7,17,9,19],
];

foreach ($worsepoint as $key => $wp) {
    $currentCluster = $response['clusters'][$key];
    $worsepointValueKeyInCluster = array_search($wp, $currentCluster);

    $worsepointsArr[$key] = [
        $currentCluster[$worsepointValueKeyInCluster],
        $currentCluster[$worsepointValueKeyInCluster+1]
    ];
}

if (!empty($worsepointsArr)) {
    $tempclusters = $response['clusters'];

    foreach (array_keys($tempclusters) as $key) {
        array_splice($tempclusters[$key], array_search($worsepointsArr[$key][0], $tempclusters[$key]), 2);
    }

    foreach ($permutationorder as $key => $nc) {
        $temp = $tempclusters;
        foreach (array_keys($temp) as $order) {
            $response['worsepoint'][$key][] = array_merge($temp[$order], $worsepointsArr[$nc[$order]]);
        }
    }
}


/*if (!empty($worsepointsArr)) {
    foreach ($worsepointsArr as $key => $currentWPA) {
        $response['worsepoint'][$key] = [];

        $next = isset($response['clusters'][$key + 1]) ? ($key + 1):0;
        $currentCluster = $response['clusters'][$key];
        $nextCluster = $response['clusters'][$next];
        $nextWPA = $worsepointsArr[$next];

        $worsepointsTemp = $worsepointsArr;
        unset($worsepointsTemp[$key]);
        reset($worsepointsTemp);

        $nextCluster = array_filter($nextCluster, function($a) use ($nextWPA) {
            return !in_array($a, $nextWPA);
        });
        //$nextCluster + = $currentWPA;

        foreach ($worsepointsTemp as $worsepointTempValue) {


            $response['worsepoint'][$key][] = $cluster;
        }
    }
}

var_dump($worsepointsArr);*/