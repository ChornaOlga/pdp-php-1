<?php

$start = microtime(true);
$cmd = "python ../pdphelper/pdphelper.py -b boxes.txt -n 4 -c \"200 200 200 990\" -r \"2 1 3 4 6 7 5 8  1\"";
for($i=0; $i<=10;$i++)
{
    exec($cmd);
}

printf('Solution was obtained in %.4F seconds', microtime(true) - $start);
echo "\n";