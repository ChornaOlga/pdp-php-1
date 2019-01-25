<?php
//ini_set('max_input_time', 300);
//phpinfo();

$worsepointsArr = [];
$worsepoint = [3, 8, 9];

$response['clusters'] = [
    [3,13,4,14,5,15],
    [6,16,8,18],
    [1,11,2,12,7,17,9,19,10,20],
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
        $nextCluster + = $currentWPA;

        foreach ($worsepointsTemp as $worsepointTempValue) {


            $response['worsepoint'][$key][] = $cluster;
        }
    }
}

var_dump($worsepointsArr);