<?php
function __autoload($class)
{
    require_once('classes/' . $class .'.php');
}


class PdpInput
{
    public static function read($filename)
    {
        $result = [];
        if (file_exists($filename))
        {
            $data = explode("\r\n", file_get_contents($filename));
            $result['count'] = (int) $data[0];
            unset($data[0]);

            foreach ($data as $row)
            {
                try
                {
                    $pointInfo = explode(' ', $row);

                    $x  = $pointInfo[1];
                    $y  = $pointInfo[2];
                    $q  = isset($pointInfo[3]) ? (float) str_replace(',', '.', $pointInfo[3]) : null;
                    $id = ($pointInfo[0] == 'depot') ? PdpPoint::DEPOT_ID : $pointInfo[0];

                    if ($id == PdpPoint::DEPOT_ID)
                    {
                        $pointType       = PdpPoint::TYPE_DEPOT;
                        $result['depot'] = new PdpPoint($id, $x, $y, $pointType, $q);
                    }
                    else
                    {
                        $pointType             = ($id <= $result['count']) ? PdpPoint::TYPE_PICKUP : PdpPoint::TYPE_DELIVERY;
                        $result['points'][$id] = new PdpPoint($id, $x, $y, $pointType, $q);
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

$pdpInfo = PdpInput::read('pdp_points.txt');
var_dump($pdpInfo);