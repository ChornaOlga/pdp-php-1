<?php

use \Litvinenko\Combinatorics\Pdp\IO;

class SolutionInfoCollector extends \Litvinenko\Common\Object
{

    public function _construct()
    {
        file_put_contents(__DIR__.'/log.txt', '');
        $this->setLog('');
        $this->setNotLoadedPaths([]);
    }

    public function logGeneratedPath($event)
    {
        $newStr = implode(' ', array_column($event->getTuple(), 'combinatorial_value')) . "\n";
        // echo $newStr;
        file_put_contents(__DIR__.'/log.txt', $newStr, FILE_APPEND);
        // $this->setLog($this->getLog() . $newStr);
    }

    public function logNotLoadedPath($event)
    {
        // $this->setNotLoadedPaths(array_merge($this->getNotLoadedPaths(), [$event->getPointSequence()]));
    }
}