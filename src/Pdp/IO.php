<?php
namespace Litvinenko\Combinatorics\Pdp;

use \Litvinenko\Common\Object;
class IO
{
    public static function readPointsFromFile($filename)
    {
        $points = [];

        if (file_exists($filename))
        {
            $pointData = explode("\r\n", file_get_contents($filename));
            $count = (int) $pointData[0];
            unset($pointData[0]);

            foreach ($pointData as $row)
            {
                try
                {
                    $pointInfo = explode(' ', $row);

                    $id = ($pointInfo[0] == 'depot') ? Point::DEPOT_ID : (int)$pointInfo[0];
                    $newPoint = new Point([
                        'id'                  => $id,
                        'x'                   => floatval($pointInfo[1]),
                        'y'                   => floatval($pointInfo[2]),
                        'box_weight'          => isset($pointInfo[3]) ? floatval($pointInfo[3]) : null,
                        'combinatorial_value' => $id,
                    ]);

                    if ($id == Point::DEPOT_ID)
                    {
                        $newPoint->setType(Point::TYPE_DEPOT);
                        $newPoint->setpairId(Point::DEPOT_ID);
                        $newPoint->setBoxWeight(0);
                        $depot = $newPoint;
                    }
                    else
                    {
                        $isPickup = ($id <= $count/2);
                        $newPoint->addData([
                            'type'          => $isPickup ? Point::TYPE_PICKUP                          : Point::TYPE_DELIVERY,
                            'pair_id' => $isPickup ? ($id + $count/2)                            : ($id - $count/2),
                            ]);

                        $points[$id] = $newPoint;
                    }
                }
                catch (Exception $e)
                {
                    throw new \Exception("Can't read row " . key($pointData) . ": ". $e->getMessage());
                }
            }

            // assign to each delivery point box weight = -1*<box weight of correspoding pickup>
            // also validate all points
            foreach($points as $point)
            {
                if ($point->getType() == Point::TYPE_DELIVERY)
                {
                    $point->setBoxWeight(- $points[$point->getPairId()]->getBoxWeight());
                }

                if ($point->isInvalid())
                {
                    throw new \Exception ("Point #" . $point->getId() . " is invalid: " . print_r($point->getValidationErrors(), true));
                }
            }

            // validate depot
            if ($depot->isInvalid())
            {
                throw new \Exception ("Depot is invalid: " . print_r($depot->getValidationErrors(), true));
            }

            return [
                'points' => $points,
                'depot'  => $depot
            ];
        }
        else
        {
            throw new \Exception("File {$filename} does not exist!");
        }

    }

    public static function readConfigFromIniFile($filename)
    {
        $result = null;
        if (file_exists($filename) && ($config = parse_ini_file($filename, true)))
        {
            $result = [
                'check_loading'        => isset($config['general']['check_loading'])        ? (bool)$config['general']['check_loading']  : null,
                'loading_checker_file' => isset($config['general']['loading_checker_file']) ? $config['general']['loading_checker_file'] : null,
                'maximize_cost'        => isset($config['general']['maximize_cost'])        ?(bool)$config['general']['maximize_cost']   : null,
                'precise'              => isset($config['general']['precise'])              ? (float)$config['general']['precise']       : null,

                'weight_capacity'      => isset($config['load']['weight_capacity'])         ? (float)($config['load']['weight_capacity'])  : null,
                'load_area'            => isset($config['load']['load_area'])               ? $config['load']['load_area']               : null,
                ];
        }

        return $result;
    }

    public static function getPathAsText(\Litvinenko\Combinatorics\Pdp\Path $path, $pointDelimiter = '-')
    {
        if ($path->getPoints())
        {
            $result = '<';
            foreach ($path->getPoints() as $point)
            {
                $result .= '' . $point->getId() . $pointDelimiter;
            }

            return substr($result, 0, -strlen($pointDelimiter)) . '> (load ' . $path->getCurrentWeight() . ')';
        }
        else
        {
            return '<empty>';
        }
    }

    public static function getReadableStepInfo($stepInfo, $stepNo = '?')
    {
        $result = '';
        if ($stepInfo)
        {
            $result .= "\n\n -------------- Step " . $stepNo . "-------------- \n\n";
            $result .= "\nactive nodes at the begin: \n";
            foreach ($stepInfo['active_nodes_at_the_begin'] as $node)
                $result .= IO::getPathAsText($node->getContent()) . ' with bound ' . $node->getOptimisticBound() . "\n";

            $result .= "\nbest full node at the begin: " . IO::getPathAsText($stepInfo['best_full_node_at_the_begin']->getContent()) . ' with bound ' . $stepInfo['best_full_node_at_the_begin']->getOptimisticBound() . "\n";
            $result .= "\nbranching node: " . IO::getPathAsText($stepInfo['branching_node']->getContent()) . ' with bound ' . $stepInfo['branching_node']->getOptimisticBound() . "\n";

            $result .= "\ngenerated children: \n";
            foreach ($stepInfo['children_generated'] as $child)
                $result .= IO::getPathAsText($child->getContent()) . ' with bound ' . $child->getOptimisticBound() . "\n";

            $result .= "\nbest full node at the end: " . IO::getPathAsText($stepInfo['best_full_node_at_the_end']->getContent()) . ' with bound ' . $stepInfo['best_full_node_at_the_end']->getOptimisticBound() . "\n";

            $result .= "\nactive nodes at the end: \n";
            foreach ($stepInfo['active_nodes_at_the_end'] as $node)
                $result .= IO::getPathAsText($node->getContent()) . ' with bound ' . $node->getOptimisticBound() . "\n";

        }

        return $result;
    }
}