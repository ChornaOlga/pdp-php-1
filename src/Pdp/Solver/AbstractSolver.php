<?php
namespace Pdp\Solver;

abstract class AbstractSolver
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