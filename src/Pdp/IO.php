<?php
namespace Litvinenko\Combinatorics\Pdp;

class IO
{
    public static function readFromFile($filename)
    {
        $result = new \Varien_Object;
        $points = [];

        if (file_exists($filename))
        {
            $data = explode("\r\n", file_get_contents($filename));
            $count = (int) $data[0];
            unset($data[0]);

            foreach ($data as $row)
            {
                try
                {
                    $pointInfo = explode(' ', $row);

                    $newPoint = new Point([
                        'x'  => floatval($pointInfo[1]),
                        'y'  => floatval($pointInfo[2]),
                        'q'  => isset($pointInfo[3]) ? (float) str_replace(',', '.', $pointInfo[3]) : null,
                        'id' => ($pointInfo[0] == 'depot') ? Point::DEPOT_ID : $pointInfo[0]
                    ]);

                    $id = $newPoint->getId();
                    if ($id == Point::DEPOT_ID)
                    {
                        $newPoint->setType(Point::TYPE_DEPOT);
                        $depot = $newPoint;
                    }
                    else
                    {
                        $newPoint->setType( ($id <= $count) ? Point::TYPE_PICKUP : Point::TYPE_DELIVERY );
                        $points[$id] = $newPoint;
                    }

                    if ($newPoint->isInvalid())
                    {
                        throw new \Exception ("Point #{$id} is invalid: " . print_r($newPoint->getValidationErrors(), true));
                    }
                }
                catch (Exception $e)
                {
                    throw new \Exception("Can't read row " . key($data) . ": ". $e->getMessage());
                }
            }
        }
        else
        {
            throw new \Exception("File {$filename} does not exist!");
        }

        return $result->setData([
            'points' => $points,
            'depot'  => $depot
        ]);
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

            return substr($result, 0, -strlen($pointDelimiter)) . '>';
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