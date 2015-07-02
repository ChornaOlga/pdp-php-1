<?php

$start = microtime(true);
$cmd = "python pdphelper/pdphelper.py -b pdphelper/boxes.txt -n 3 -c \"50 50 50 200\" -r \"2 5 1 3 4 6  999\" -p";
echo "\n".$cmd."\n"."\n";
    system($cmd);

printf('Solution was obtained in %.4F seconds', microtime(true) - $start);
echo "\n";