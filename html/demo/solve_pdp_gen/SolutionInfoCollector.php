<?php

use \Litvinenko\Combinatorics\Pdp\IO;
class SolutionInfoCollector extends \Litvinenko\Common\Object
{

    public function _construct()
    {
        $this->setLog('');
        $this->setNotLoadedPaths([]);
    }

    public function logGeneratedPath($event)
    {
        $newStr = implode(' ', array_column($event->getTuple(), 'combinatorial_value')) . "\n";
        $this->setLog($this->getLog() . $newStr);
    }

    public function logNotLoadedPath($event)
    {
        $this->setNotLoadedPaths(array_merge($this->getNotLoadedPaths(), [$event->getPointSequence()]));
    }
}