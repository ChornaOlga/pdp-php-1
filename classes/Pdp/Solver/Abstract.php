<?php

abstract class Pdp_Solver_Abstract
{
    protected $depot;
    protected $points;
    protected $checkLoading;
    protected $loadingCheckerFile;

    public function __construct($depot, $points, $checkLoading, $loadingCheckerFile)
    {
        $this->depot              = $depot;
        $this->points             = $points;
        $this->checkLoading       = $checkLoading;
        $this->loadingCheckerFile = $loadingCheckerFile;
    }

    abstract public function getSolution();
}