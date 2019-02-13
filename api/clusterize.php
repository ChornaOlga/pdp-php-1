<?php
require_once '../vendor/autoload.php';
$response = [];

// $_REQUEST['params'] = '{
//     "cluster_count":"3",
//     "points":[
//         [100,100],
//         [200,200],
//         [300,300],
//         [400,400],
//         [500,500],
//         [600,600],
//         [700,700],
//         [800,800],
//         [900,900],
//         [1000,1000],
//         [1100,1100],
//         [1200,1200]
//     ]
// }';
//var_dump($_REQUEST['params']);


function getMiddlePointBetween(array $firstPoint, array $secondPoint)
{
    $result = [];
    foreach ($firstPoint as $dimension => $coord) {
        $result[$dimension] = ((float)$firstPoint[$dimension] + (float)$secondPoint[$dimension])/2;
    }

    return $result;
}

function array_flatten($array) {

   $return = array();
   foreach ($array as $key => $value) {
       if (is_array($value)){ $return = array_merge($return, array_flatten($value));}
       else {$return[$key] = $value;}
   }
   return $return;

}


if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'])) && ($params instanceof stdClass))
{
    try {

        $points = [];

        // first N points are pickups, second N - deliveries.
        // we make N point, where i-th point is center between i-th pickup and i-th delivery
        $kmeansPoints = [];
        $n = count($params->points) / 2;
        for ($i = 0; $i < $n; $i++)
        {
            $middle = getMiddlePointBetween($params->points[$i], $params->points[$n + $i]);
            $kmeansPoints[$i] = [
                'pdp_points'    => [$i, $n + $i],
                'internal_data' => [$i, $middle[0], $middle[1], 0]
            ];
        }

        // $data = array(
        //     array('Lorem',3,5),
        //     array('ipsum',5,3),
        //     array('dolor',4,2),
        //     array('sit',4,5),
        //     array('amet',2,3),
        //     array('consectetur',5,4),
        //     array('adipisicing',4,4),
        //     array('elit',3,2),
        //     array('sed',2,6),
        //     array('do',17,16),
        //     array('eiusmod',16,18),
        //     array('tempor',18,18),
        //     array('incididunt',16,17),
        //     array('ut',14,18),
        //     array('labore',18,15),
        //     array('et',16,17),
        //     array('dolore',17,16),
        //     array('magna',19,19),
        //     array('aliqua',16,19),
        // );

        $kmeans = new KMeans();
        $kmeans
            ->setData(array_column($kmeansPoints,'internal_data'))
            ->setXKey(1)
            ->setYKey(2)
            ->setClusterCount($params->cluster_count)
            ->solve();

        $worsepointsArr = [];
        $output = [];
        // return ids of points in each cluster
        foreach ($kmeans->getClusters() as $key => $cluster) {
            $kMeansPointsIds = array_column($cluster->getData(),0); // ids of k-means points in this cluster

            //worse point in each cluster
            $temp = [];

            for ($i = 0; $i < count($kMeansPointsIds); $i++) {
                $xdelta = abs($params->points[$kMeansPointsIds[$i]][0] - $cluster->getX())**2;
                $ydelta = abs($params->points[$kMeansPointsIds[$i]][1] - $cluster->getY())**2;
                $temp[] = sqrt($xdelta + $ydelta);
            }

            $worsepoint = null;
            if (!empty($temp)) {
                $worsepoint = $kMeansPointsIds[array_search(max($temp), $temp)];
                $WPinC = $params->points[$worsepoint];
                $output[] = $worsepoint+1;
            }

            $response['clusters'][$key] = array_flatten(array_intersect_key(array_column($kmeansPoints,'pdp_points'), array_flip($kMeansPointsIds)));
            if (!is_null($worsepoint)) {
                $currentCluster = $response['clusters'][$key];
                $worsepointValueKeyInCluster = array_search($worsepoint, $currentCluster);

                $worsepointsArr[$key] = [
                    $currentCluster[$worsepointValueKeyInCluster],
                    $currentCluster[$worsepointValueKeyInCluster+1]
                ];
            }

            $worsepoint = null;
        }

        file_put_contents('output.txt', implode(', ',$output));

        /*if (!empty($worsepointsArr)) {
            foreach ($worsepointsArr as $key => $worsepointValue) {
                $response['worsepoint'][$key] = [];

                $worsepointsTemp = $worsepointsArr;
                unset($worsepointsTemp[$key]);
                reset($worsepointsTemp);


                foreach ($worsepointsTemp as $worsepointTempValue) {
                    $next = isset($response['clusters'][$key + 1]) ? $key + 1:0;
                    $currentCluster = $response['clusters'][$key];
                    $nextCluster = $response['clusters'][$next];

                    $response['worsepoint'][$key][] = $cluster;
                }
            }
        }*/
        $response['worsepoint'] = [];
        $permutationorder2to5 = [[[1, 0]],
            [[1, 2, 0],[2, 0, 1]],
            [[1, 2, 3, 0],[1, 3, 0, 2],[2, 0, 3, 1],[2, 3, 1, 0],[3, 0, 1, 2],[3, 2, 0, 1]],
            [[1, 2, 4, 0, 3],[1, 4, 3, 0, 2],[4, 2, 3, 0, 1],[4, 3, 0, 2, 1],[1, 4, 0, 2, 3],
                [1, 3, 0, 4, 2],[1, 3, 4, 2, 0],[3, 2, 4, 1, 0],[3, 4, 0, 1, 2],[3, 2, 0, 4, 1],
                [4, 2, 0, 1, 3], [4, 0, 1, 2, 3], [3, 0, 1, 4, 2], [3, 0, 4, 2, 1], [3, 4, 1, 2, 0],
                [2, 4, 3, 1, 0],[2, 0, 3, 4, 1],[2, 0, 4, 1, 3],[4, 0, 3, 1, 2],[4, 3, 1, 0, 2],
                [2, 3, 4, 0, 1],[2, 4, 1, 0, 3],[2, 3, 1, 4, 0],[1, 2, 3, 4, 0]]];

        if (count($worsepointsArr)===intval($params->cluster_count)) {
            $tempclusters = $response['clusters'];

            foreach (array_keys($tempclusters) as $key) {
                array_splice($tempclusters[$key], array_search($worsepointsArr[$key][0], $tempclusters[$key]), 2);
            }

            foreach ($permutationorder2to5[count($worsepointsArr)-2] as $key => $nc) {
                $temp = $tempclusters;
                foreach (array_keys($temp) as $order) {
                    $response['worsepoint'][$key][] = array_merge($temp[$order], $worsepointsArr[$nc[$order]]);
                }
            }
        }

        // $clusters = $kmeans->getClusters();
        // foreach ($clusters as $cluster) {
        //     print_r($cluster->getData());
        // }
    }
    catch (Exception $e) {
        $response['errors'][] = 'Unexpected error: ' . $e->getMessage();
    }
}
else
{
    $response['errors'][] = 'Incorrect JSON input';
}

echo json_encode($response);