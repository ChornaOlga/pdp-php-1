<?php

include_once ('KMeans.php');

$data = array(
    array('Lorem',3,5),
    array('ipsum',5,3),
    array('dolor',4,2),
    array('sit',4,5),
    array('amet',2,3),
    array('consectetur',5,4),
    array('adipisicing',4,4),
    array('elit',3,2),
    array('sed',2,6),
    array('do',17,16),
    array('eiusmod',16,18),
    array('tempor',18,18),
    array('incididunt',16,17),
    array('ut',14,18),
    array('labore',18,15),
    array('et',16,17),
    array('dolore',17,16),
    array('magna',19,19),
    array('aliqua',16,19),
);

// perform analysis
$kmeans = new KMeans();
$kmeans
	->setData($data)
	->setXKey(1)
	->setYKey(2)
	->setClusterCount(3)
	->solve();

$clusters = $kmeans->getClusters();
foreach ($clusters as $cluster) {
    print_r($cluster->getData());
}