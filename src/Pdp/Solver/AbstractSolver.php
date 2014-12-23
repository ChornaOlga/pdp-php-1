<?php
namespace Pdp\Solver;

abstract class AbstractSolver
{
    protected $depot;
    protected $points;
    protected $maximizeCost;
    protected $checkLoading;
    protected $loadingCheckerFile;

    public function __construct($depot, $points, $maximizeCost, $checkLoading, $loadingCheckerFile)
    {
        $this->depot              = $depot;
        $this->points             = $points;
        $this->maximizeCost       = $maximizeCost;
        $this->checkLoading       = $checkLoading;
        $this->loadingCheckerFile = $loadingCheckerFile;
    }


}