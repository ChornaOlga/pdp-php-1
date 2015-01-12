<?php
namespace Litvinenko\Combinatorics\Pdp\Solver;

use Litvinenko\Combinatorics\BranchBound\Node;
use Litvinenko\Combinatorics\Pdp\Helper;
use Litvinenko\Combinatorics\Pdp\Path;
use Litvinenko\Combinatorics\Pdp\Point;
// use Litvinenko\Combinatorics\Common\Generators\Recursive\PermutationWithRepetitionsGenerator as Generator;
use Litvinenko\Combinatorics\Pdp\Generators\Recursive\PdpPermutationGenerator as Generator;
use Litvinenko\Combinatorics\Pdp\Evaluator\PdpEvaluator as Evaluator;

use Litvinenko\Common\App;
class GenerationSolver extends \Litvinenko\Combinatorics\BranchBound\AbstractSolver
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

        'precise'              => 'required|float_strict',
    );

    public function _construct()
    {
        parent::_construct();

        $optimisticBound = ($this->getMaximizeCost()) ? 0 : PHP_INT_MAX;

        $this->setInitialNodeContent(new Path(['points' => [$this->getDepot()] ]));
        $this->setInitialNodeOptimisticBound($optimisticBound);

        $this->setHelper(new Helper);
    }

    public function getSolution()
    {
        $this->getHelper()->validate($this);
        $this->getHelper()->validate($this->getPoints());

        return parent::getSolution();
    }

    protected function _compareNodes($firstNode, $secondNode)
    {
        // for now, just compare optimistic bounds
        return ($this->_compareCosts($firstNode->getOptimisticBound(), $secondNode->getOptimisticBound()));
    }

    protected function _generateChildrenOf($node)
    {
        $result = [];
        if (!$this->_nodeIsCompleteSolution($node))
        {
            $generator = new Generator([
                'tuple_length'        => Point::getPointCount($this->getPoints()),
                'generating_elements' => $this->_getGeneratorDataFromPoints($this->getPoints()),
                'current_path'        => $node->getContent(),
                'weight_capacity'     =>  $this->getWeightCapacity()
            ]);

            $points    = $this->_getGeneratorDataFromPoints($node->getContent()->getPoints());
            $newPointSequences = $this->_getPointSequencesFromGeneratorData($generator->generateNextObjects($points));

            foreach ($newPointSequences as $newPointSequence)
            {
                $path = new Path(['points' => $newPointSequence]);
                $newNode = new Node(['content' => $path]);
                $newNode->setOptimisticBound((new Evaluator)->getBound($newNode, Evaluator::BOUND_TYPE_OPTIMISTIC , [
                    'parent_node'       => $node,
                    'total_point_count' => Point::getPointCount($this->getPoints())
                    ]));
                 $result[] = $newNode;
            }
        }
        return $result;
    }

    protected function _nodeIsCompleteSolution($node)
    {
        return ($node->getContent()->getPointsCount() == Point::getPointCount($this->getPoints()));
    }

    /**
     * Helper function for packing points to data neede for generator
     *
     * @param  array $points
     *
     * @return array
     */
    protected function _getGeneratorDataFromPoints($points)
    {
        $result = [];
        foreach ($points as $point)
        {
            $result[] = [
                'id'                  => $point->getId(),
                'value'               => $point,
                'combinatorial_value' => $point->getCombinatorialValue()
            ];
        }

        return $result;
    }

    protected function _getPointSequencesFromGeneratorData($generatorData)
    {
        $result = [];
        foreach ($generatorData as $pointSequence)
        {
            $sequence = [];
            foreach ($pointSequence as $point)
            {
                $sequence[] = $point['value'];
            }
            $result[] = $sequence;
        }

        return $result;
    }

    protected function _logEvent($eventName, $data)
    {
        App::dispatchEvent("branch_bound_{$eventName}", $data);
    }
}
