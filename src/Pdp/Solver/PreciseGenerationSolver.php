<?php
namespace Litvinenko\Combinatorics\Pdp\Solver;

use Litvinenko\Combinatorics\Pdp\IO;
use Litvinenko\Combinatorics\Pdp\Helper;
use Litvinenko\Combinatorics\Pdp\Path;
use Litvinenko\Combinatorics\Pdp\Point;
// use Litvinenko\Combinatorics\Common\Generators\Recursive\PermutationWithRepetitionsGenerator as Generator;
use Litvinenko\Combinatorics\Common\Evaluator\AbstractEvaluator;
use Litvinenko\Combinatorics\Pdp\Generators\Recursive\PrecisedPdpPermutationGenerator as Generator;

use Litvinenko\Common\App;

class PreciseGenerationSolver extends \Litvinenko\Combinatorics\Common\Solver\AbstractSolver
{

    protected $dataRules = array(
        // rules from abstract solver
        'maximize_cost'        => 'required|boolean',

        // specifically data rules for this class
        'depot'                => 'required|object:\Litvinenko\Combinatorics\Pdp\Point',
        'points'               => 'required|array',
        'weight_capacity'      => 'required|float_strict',
        'load_area'            => 'required|array',
        'check_loading'        => 'required|boolean',
        'loading_checker_file' => 'required',

        'evaluator' => 'required|object:\Litvinenko\Combinatorics\Common\Evaluator\AbstractEvaluator',

        'precise' => 'required|float_strict',
    );

    protected $_boxFileName = 'boxes.txt';

    public function _construct()
    {
        parent::_construct();
        $this->setHelper(new Helper);
    }

    public function getSolution()
    {
        $this->validate();
        $this->getHelper()->validateObjects($this->getPoints());

        $generator = new Generator([
            'tuple_length'        => Point::getPointCount($this->getPoints()),
            'generating_elements' => Helper::getGeneratorDataFromPoints($this->getPoints()),
            'weight_capacity'     => $this->getWeightCapacity(),

            'precise'             => $this->getPrecise(),
            'metrics'             => $this->getEvaluator()->getMetrics(),
            'initial_object'      => Helper::getGeneratorDataFromPoints([$this->getDepot()]),

            'log_steps' => true
        ]);

        $pointSequences = Helper::getPointSequencesFromGeneratorData($generator->generateAll());

        $bestPointSequence = null;
        foreach ($pointSequences as $pointSequence)
        {
            $pointSequence[] = $this->getDepot();
            $currentCost = $this->_getCost($pointSequence);
            if ((is_null($bestPointSequence) || ($this->_compareCosts($currentCost, $bestCost) === 1)) && $this->canLoad($pointSequence))
            {
                $bestPointSequence = $pointSequence;
                $bestCost          = $currentCost;
            }
        }

        $this->setGeneratedPointSequences($pointSequences);
        return new Path(['points' => $bestPointSequence]);
    }

    public function _getCost($pointSequence)
    {
        $path = ($pointSequence instanceof Path) ? $pointSequence : (new Path(['points' => $pointSequence]));
        return $this->getEvaluator()->getBound($path, AbstractEvaluator::BOUND_TYPE_OPTIMISTIC);
    }

    public function canLoad($pointSequence)
    {
        $result = true;

        $points = Helper::removeDepotFromPointSequence($pointSequence);
        if ($this->getCheckLoading())
        {
            $file = $this->getLoadingCheckerFile();
            if (file_exists($file))
            {
                file_put_contents($this->_boxFileName, IO::getBoxesTextForExternalPdpHelper($points));

                $cmdString = "{$file}" .
                                " -b {$this->_boxFileName}" .
                                " -n "   . (int)(count($points)/2) .
                                " -c \"" . implode(' ', $this->getLoadArea()) . ' ' . $this->getWeightCapacity() . "\"" .
                                " -r \""  . implode(' ', Point::getPointIds($points)) . "  1\"";
                $cmdResult = exec($cmdString);
                echo $cmdResult . "\n";
                $result = ($cmdResult == 'True');
            }
            else
            {
                throw new \Exception("loading checker file '{$file}' doesn't exist!");
            }
            // pdphelper.exe -b Korobki.txt -n 4 -c "20 20 20 100" -r "2 1 5 6 3 7 4 8  44"
        }

        return $result;
    }
}
