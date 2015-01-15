<?php

class SolutionInfoCollector extends \Litvinenko\Common\Object
{

    public function _construct()
    {
        $this->setLog('');
    }

    public function logGeneratedPath($event)
    {
        $newStr = implode(' ', array_column($event->getTuple(), 'combinatorial_value')) . "\n";
        $this->setLog($this->getLog() . $newStr);
    }
}