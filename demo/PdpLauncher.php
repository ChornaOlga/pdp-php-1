<?php

class PdpLauncher extends Litvinenko\Common\Object
{
  protected function _construct()
  {
    if (!$this->hasData('avaliable_methods'))
    {
      $this->setData('avaliable_methods', [
      'gen'          => 'cd solve_pdp_gen && php run.php',
      'branch_bound' => 'cd solve_pdp_branch_bound && php run.php'
      ]);
    }

    if (!$this->hasData('pdppoints_file'))
    {
      $this->setData('pdppoints_file', 'pdp_points.txt');
    }

    if (!$this->hasData('pdpconfig_file'))
    {
      $this->setData('pdpconfig_file', 'pdp_config.ini');
    }


    if (!$this->hasData('depot_coords'))
    {
      $this->setData('depot_coords', [200,200]);
    }
  }

  public function generateRandomData($pairCount)
  {
    $minRandomCoord     = 1;
    $maxRandomCoord     = 500000;
    $minRandomBoxSize   = 50;
    $maxRandomBoxSize   = 1;
    $minRandomBoxWeight = 100;
    $maxRandomBoxWeight = 1;

    $result = [];
    for ($i = 0; $i < ($pairCount*2); $i++)
    {
      // coords
      $result[$i][0] = rand($minRandomCoord,$maxRandomCoord);
      $result[$i][1] = rand($minRandomCoord,$maxRandomCoord);

      if ($i < $pairCount)
      {
        // box dimensions
        $result[$i][2] = rand($minRandomBoxSize,$maxRandomBoxSize);
        $result[$i][3] = rand($minRandomBoxSize,$maxRandomBoxSize);
        $result[$i][4] = rand($minRandomBoxSize,$maxRandomBoxSize);

        // box weight
        $result[$i][5] = rand($minRandomBoxWeight,$maxRandomBoxWeight);
      }
    }

    return $result;
  }

  public function writePdpPointsContent($filename, $data)
  {
    if (!file_exists($filename))
    {
      return false;
    }
    else
    {
      $content = count($data) . PHP_EOL;

      $i = 1;
      foreach ($data as $row)
      {
          $content .= $i++ . ' ' . implode(' ', $row) . PHP_EOL;
      }

      $depotData = (isset($data['depot'])) ? $data['depot'] : $this->getDepotCoords();
      $content .= 'depot ' . implode(' ', $depotData);

      file_put_contents($filename, $content);
      return true;
    }
  }

  public function getSolution($method, $data = [], $specialParams = [])
  {
      $result = [];
      if (in_array($method, array_keys($this->getAvaliableMethods())))
      {
          if ($data) $this->writePdpPointsContent($this->getPdppointsFile(), $data);

          // for GEN method, we write precise to PDP config file
          if (($method == 'gen') && $specialParams)
          {
            $filename = $this->getPdpconfigFile();
            if ($ini = parse_ini_file($filename, true))
            {
              if ($specialParams['precise'])         $ini['general']['precise']      = $specialParams['precise'];
              if ($specialParams['weight_capacity']) $ini['load']['weight_capacity'] = $specialParams['weight_capacity'];
              if ($specialParams['load_area'])       $ini['load']['load_area']       = $specialParams['load_area'];

              write_php_ini($ini, $filename);
            }
            else
            {
              $result['errors'][] = "Pdpconfig file '". $filename . "doesn't exist!";
            }
          }
          $solutionCmdCommand = $this->getAvaliableMethods()[$method];

          $start = microtime(true);
          if (($cmdResultRaw = exec($solutionCmdCommand)) &&  ($cmdResult = json_decode($cmdResultRaw)) && ($cmdResult instanceof stdClass))
          {
             $result['path']          = $cmdResult->path;
             $result['path_cost']     = $cmdResult->path_cost;
             $result['solution_time'] = $cmdResult->solution_time; // this time is running time of solution method (can differ from real run time)
             $result['info']          = (array) $cmdResult->info;
          }
          else
          {
             $result['errors'][] = "Internal error. Cannot run solution script or its output is incorrect. raw response is: $cmdResultRaw";
          }
          $result['exec_time'] = microtime(true) - $start;// this time is REAL running time of script
      }
      else
      {
          $result['errors'][] = "Invalid solution method '{$method}'. Avaliable methods are '" . implode("','", array_keys($this->getAvaliableMethods())) . "'";
      }
      return $result;
  }
}

function write_php_ini($array, $file)
{
    $res = array();
    foreach($array as $key => $val)
    {
        if(is_array($val))
        {
            $res[] = "[$key]";
            foreach($val as $skey => $sval)
            {
              if(is_array($sval))
              {
                foreach($sval as $thirdLevelKey => $thirdLevelVal)
                {
                  $res[] = "{$skey}[{$thirdLevelKey}] = ". getIniValue($thirdLevelVal);
                }
              }
              else
              {
                $res[] = "$skey = ".getIniValue($sval);
              }
            }
        }
        else $res[] = "$key = ".getIniValue($val);
    }
    safefilerewrite($file, implode("\r\n", $res));
}

function getIniValue($value)
{
  if ($value === "1") return 'yes';
  if ($value === "") return 'no';
  if (is_numeric($value)) return $value;
  return '"'.$value.'"';
}
function safefilerewrite($fileName, $dataToSave)
{    if ($fp = fopen($fileName, 'w'))
    {
        $startTime = microtime();
        do
        {            $canWrite = flock($fp, LOCK_EX);
           // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
           if(!$canWrite) usleep(round(rand(0, 100)*1000));
        } while ((!$canWrite)and((microtime()-$startTime) < 1000));

        //file was locked so now we can store information
        if ($canWrite)
        {            fwrite($fp, $dataToSave);
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

}