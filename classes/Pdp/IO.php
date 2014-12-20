<?php

class Pdp_IO
{
    public static function read($filename)
    {
        $result         = new StdClass;
        $result->points = [];

        if (file_exists($filename))
        {
            $data = explode("\r\n", file_get_contents($filename));
            $result->count = (int) $data[0];
            unset($data[0]);

            foreach ($data as $row)
            {
                try
                {
                    $pointInfo = explode(' ', $row);

                    $x  = $pointInfo[1];
                    $y  = $pointInfo[2];
                    $q  = isset($pointInfo[3]) ? (float) str_replace(',', '.', $pointInfo[3]) : null;
                    $id = ($pointInfo[0] == 'depot') ? Pdp_Point::DEPOT_ID : $pointInfo[0];

                    if ($id == Pdp_Point::DEPOT_ID)
                    {
                        $pointType     = Pdp_Point::TYPE_DEPOT;
                        $result->depot = new Pdp_Point($id, $x, $y, $pointType, $q);
                    }
                    else
                    {
                        $pointType           = ($id <= $result->count) ? Pdp_Point::TYPE_PICKUP : Pdp_Point::TYPE_DELIVERY;
                        $result->points[$id] = new Pdp_Point($id, $x, $y, $pointType, $q);
                    }
                }
                catch (Exception $e)
                {
                    throw new Exception("Can't read row " . key($data) . ": ". $e->getMessage());
                }
            }
        }
        else
        {
            throw new Exception("File {$filename} does not exist!");
        }
        return $result;
    }
}