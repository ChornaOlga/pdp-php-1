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

    public static function getPathAsText(\Litvinenko\Combinatorics\Pdp\Path $path, $pointDelimiter = '->')
    {
        $result = '';
        foreach ($path->getPoints() as $point)
        {
            $result .= $point->getId() . $pointDelimiter;
        }

        return substr($result, 0, -strlen($pointDelimiter));
    }
}