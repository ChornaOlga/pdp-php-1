<?php
namespace Litvinenko\Combinatorics\Pdp\Solver;

use Litvinenko\Combinatorics\Pdp\Helper;
use Litvinenko\Combinatorics\Pdp\Path;
use Litvinenko\Combinatorics\Pdp\Point;
// use Litvinenko\Combinatorics\Common\Generators\Recursive\PermutationWithRepetitionsGenerator as Generator;
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
            'metrics'           => $this->getEvaluator()->getMetrics()
        ]);

        $pointSequences = Helper::getPointSequencesFromGeneratorData($generator->generateAll());

        $bestPointSequence = null;
        foreach ($pointSequences as $pointSequence)
        {
            $currentCost = $this->getCost($pointSequence);
            if (is_null($bestPointSequence) || $this->_compareCosts($currentCost, $bestCost))
            {
                $bestPointSequence = $pointSequence;
                $bestCost          = $currentCost;
            }
        }

        $this->setGeneratedPointSequences($pointSequences);
        return new Path(['points' => $bestPointSequence]);
    }
}
