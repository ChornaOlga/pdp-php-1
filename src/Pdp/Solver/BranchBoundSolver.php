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
class BranchBoundSolver extends \Litvinenko\Combinatorics\BranchBound\AbstractSolver
{
    protected $dataRules = array(
        // rules from abstract solver
        'maximize_cost'                 => 'required|boolean',
        'initial_node_content'          => 'required',
        'initial_node_optimistic_bound' => 'required|float_strict',

        // specifically data rules for this class
        'depot'                => 'required|object:\Litvinenko\Combinatorics\Pdp\Point',
        'points'               => 'required|array',
        'weight_capacity'      => 'required|float_strict',
        'load_area'            => 'required|array',
        'check_loading'        => 'required|boolean',
        'check_loading_command_prefix' => 'required',

        'evaluator' => 'required|object:\Litvinenko\Combinatorics\Common\Evaluator\AbstractEvaluator'
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
        $this->getHelper()->validateObjects($this->getPoints());

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
                'generating_elements' => Helper::getGeneratorDataFromPoints($this->getPoints()),
                'current_path'        => $node->getContent(),
                'weight_capacity'     => $this->getWeightCapacity()
            ]);
            $generator->validate();

            $points            = Helper::getGeneratorDataFromPoints($node->getContent()->getPoints());
            $newPointSequences = Helper::getPointSequencesFromGeneratorData($generator->generateNextObjects($points));

            foreach ($newPointSequences as $newPointSequence)
            {
                $path    = new Path(['points'  => $newPointSequence]);
                $newNode = new Node(['content' => $path]);

                $newNode->setOptimisticBound($this->getEvaluator()->getBound($path, Evaluator::BOUND_TYPE_OPTIMISTIC , [
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
        return (($node->getContent()->getPointsCount() - 1) == Point::getPointCount($this->getPoints())); // -1 because first point is depot
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
