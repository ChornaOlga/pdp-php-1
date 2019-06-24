<?php
require_once '../vendor/autoload.php';
$response = [];


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

function sub_array(array $haystack, array $needle)
{
    return array_intersect_key($haystack, array_flip($needle));
}

function get_area(array $AllX, array $AllY){

    if(!empty($AllX)&&!empty($AllY)) {
        $Area = (max($AllX) - min($AllX)) * (max($AllY) - min($AllY));
    }
    else $Area = 0;

    return $Area;
}

function euclidean_distance(array $firstpoint, array $middle, array $secondpoint){
    $distance = 0;
    $xdeltaone = abs($firstpoint[0] - $middle[0])**2;
    $ydeltaone = abs($firstpoint[1] - $middle[1])**2;
    $xdeltatwo = abs($secondpoint[0] - $middle[0])**2;
    $ydeltatwo = abs($secondpoint[1] - $middle[1])**2;
    $distance = sqrt($xdeltaone + $ydeltaone) + sqrt($xdeltatwo + $ydeltatwo);
    return $distance;
}

function intercluster_distance(array $firstpoint, array $secondpoint, array $other_points){
    $distances = [];
    foreach ($other_points as $key => &$value) {
        $xdelta1 = abs($firstpoint[0] - $value[0])**2;
        $ydelta1 = abs($firstpoint[1] - $value[1])**2;
        $xdelta2 = abs($secondpoint[0] - $value[0])**2;
        $ydelta2 = abs($secondpoint[1] - $value[1])**2;
        $distances[$key] = min(sqrt($xdelta1 + $ydelta1),sqrt($xdelta2 + $ydelta2));
    }
    $result[0] = min($distances);
    $result[1] = array_search($result[0],$distances);
    return $result;
}


if (!empty($_REQUEST) && isset($_REQUEST['params']) && ($params = json_decode($_REQUEST['params'])) && ($params instanceof stdClass))
{
    try {

        $points = [];
        $depot_coords = json_decode(json_encode($params->depot), True);

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


        $kmeans = new KMeans();
        $kmeans
            ->setData(array_column($kmeansPoints,'internal_data'))
            ->setXKey(1)
            ->setYKey(2)
            ->setClusterCount($params->cluster_count)
            ->solve();

        $TotalClusterX = [];
        $TotalClusterY = [];
        $worsepointsArr = [];
        $output = [];
        $blackpermut = [];
        // return ids of points in each cluster
        foreach ($kmeans->getClusters() as $key => $cluster) {
            $kMeansPointsIds = array_column($cluster->getData(),0); // ids of k-means points in this cluster
            $DopPoints = [];
            foreach ($kMeansPointsIds as &$value) {
                $DopPoints[] = $value+$n;
            }
            $AllPointsIds = array_keys($params->points);
            $AnotherPointsIds = array_diff($AllPointsIds, $kMeansPointsIds, $DopPoints);
            $AnotherPoints = sub_array($params->points, $AnotherPointsIds);
            //worse point in each cluster
            $temp = [];

            for ($i = 0; $i < count($kMeansPointsIds); $i++) {
                //Euclidean distance
                //$depot = [$cluster->getX(), $cluster->getY()];
                //$temp[$kMeansPointsIds[$i]] =
                //    euclidean_distance(
                //        $params->points[$kMeansPointsIds[$i]],
                //        $depot,
                //        $params->points[($kMeansPointsIds[$i]+$n)]);
                //Intercluster distance
                $distanceandpoint = intercluster_distance(
                        $params->points[$kMeansPointsIds[$i]],
                        $params->points[($kMeansPointsIds[$i]+$n)],
                        $AnotherPoints);
                $temp[0][$kMeansPointsIds[$i]] = $distanceandpoint[0];
                $temp[1][$kMeansPointsIds[$i]] = $distanceandpoint[1];
            }

            $worsepoint = null;
            if (!empty($temp)) {
                $worsepoint = array_search(min($temp[0]), $temp[0]);
                $nearestpoint = $temp[1][$worsepoint];
                $WPinC = $params->points[$worsepoint];
                $output[] = $worsepoint+1;
                $blackpermut[0][] = $key;
                $blackpermut[1][] = ($nearestpoint+1)%$n;
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
        for ($i = 0; $i < count($params->points); $i++) {
            $TotalClusterX[] = (float)$params->points[$i][0];
            $TotalClusterY[] = (float)$params->points[$i][1];
        }
        $TotalClusterX[] = (float)$depot_coords['x'];
        $TotalClusterY[] = (float)$depot_coords['y'];
        $TotalArea = get_area($TotalClusterX, $TotalClusterY);
        $response['area'] = $TotalArea;


        file_put_contents('output.txt', implode(', ',$output));
        file_put_contents('output.txt', "\n", FILE_APPEND);
        file_put_contents('output.txt', implode(', ',$blackpermut[1]), FILE_APPEND);


        //reordering points in clusters according to cyclic permutations
        $response['worsepoint'] = [];
        /*$permutationorder2to5 = [[[1, 0],[0, 1]],
            [[1, 2, 0],[2, 0, 1],[0, 1, 2]],
            [[1, 2, 3, 0],[1, 3, 0, 2],[2, 0, 3, 1],[2, 3, 1, 0],[3, 0, 1, 2],[3, 2, 0, 1],[0, 1, 2, 3]],
            [[1, 2, 4, 0, 3],[1, 4, 3, 0, 2],[4, 2, 3, 0, 1],[4, 3, 0, 2, 1],[1, 4, 0, 2, 3],
                [1, 3, 0, 4, 2],[1, 3, 4, 2, 0],[3, 2, 4, 1, 0],[3, 4, 0, 1, 2],[3, 2, 0, 4, 1],
                [4, 2, 0, 1, 3], [4, 0, 1, 2, 3], [3, 0, 1, 4, 2], [3, 0, 4, 2, 1], [3, 4, 1, 2, 0],
                [2, 4, 3, 1, 0],[2, 0, 3, 4, 1],[2, 0, 4, 1, 3],[4, 0, 3, 1, 2],[4, 3, 1, 0, 2],
                [2, 3, 4, 0, 1],[2, 4, 1, 0, 3],[2, 3, 1, 4, 0],[1, 2, 3, 4, 0],[0, 1, 2, 3, 4]]];*/
        $permutationorder2to5 = [[[1, 0]],
            [[1, 2, 0],[2, 0, 1]],
            [[1, 2, 3, 0],[1, 3, 0, 2],[2, 0, 3, 1],[2, 3, 1, 0],[3, 0, 1, 2],[3, 2, 0, 1]],
            [[1, 2, 4, 0, 3],[1, 4, 3, 0, 2],[4, 2, 3, 0, 1],[4, 3, 0, 2, 1],[1, 4, 0, 2, 3],
                [1, 3, 0, 4, 2],[1, 3, 4, 2, 0],[3, 2, 4, 1, 0],[3, 4, 0, 1, 2],[3, 2, 0, 4, 1],
                [4, 2, 0, 1, 3], [4, 0, 1, 2, 3], [3, 0, 1, 4, 2], [3, 0, 4, 2, 1], [3, 4, 1, 2, 0],
                [2, 4, 3, 1, 0],[2, 0, 3, 4, 1],[2, 0, 4, 1, 3],[4, 0, 3, 1, 2],[4, 3, 1, 0, 2],
                [2, 3, 4, 0, 1],[2, 4, 1, 0, 3],[2, 3, 1, 4, 0],[1, 2, 3, 4, 0] ]];

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