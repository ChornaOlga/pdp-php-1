<?php
namespace Litvinenko\Combinatorics\Pdp;

class Helper extends \Litvinenko\Common\Object
{
    const LOG_FILE       = 'log/system.log';
    const ERROR_LOG_FILE = 'log/error.log';

    protected $dataRules = array(
        'log_errors'       => 'boolean',
        'throw_exceptions' => 'boolean'
    );


    protected function _construct()
    {
        if (!$this->hasLogErrors()) $this->setLogErrors(true);
        if (!$this->hasThrowExceptions()) $this->setThrowExceptions(true);
    }

    public static function log($message)
    {
        file_put_contents(self::LOG_FILE, date("Y-m-d H:i:s") . ": $message\n", FILE_APPEND);
    }

    public static function logError($message)
    {
        file_put_contents(self::ERROR_LOG_FILE, date("Y-m-d H:i:s") . " Error occured:\n$message\n", FILE_APPEND);
    }

    public function validateObjects($objects)
    {
        if (!is_array($objects))
        {
            $objects = array($objects);
        }

        foreach($objects as $object)
        {
            if (!($object instanceof \Litvinenko\Common\Object))
            {
                $message = 'given ' . gettype($object) . ' is not instance of \Litvinenko\Common\Object';
                if ($this->getThrowExceptions()) throw new \Exception($message);
                if ($this->getLogErrors()) self::logError($message);
            }
            else
            {
                try
                {
                    $object->validate();
                }
                catch (\Litvinenko\Common\Object\Exception $e)
                {
                    if ($this->getThrowExceptions()) throw new \Exception($e->getMessage());
                    if ($this->getLogErrors()) self::logError($e->getMessage());
                }
            }
        }
    }

    /**
     * Helper function for packing points to data neede for generator
     *
     * @param  array $points
     *
     * @return array
     */
    public static function getGeneratorDataFromPoints(array $points)
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

    public static function getPointSequencesFromGeneratorData($generatorData)
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
}