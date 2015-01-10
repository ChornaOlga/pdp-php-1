<?php
namespace Litvinenko\Combinatorics\Pdp;

class Helper extends \Litvinenko\Common\Object
{
    const LOG_FILE       = 'log/system.log';
    const ERROR_LOG_FILE = 'log/error.log';

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

    public function validate($objects)
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
            if ($object->isInvalid())
            {
                $message = "object " .  get_class($object) . " is not valid: \n" . print_r($object->getValidationErrors(),true) /*. var_export($object, true)*/;
                if ($this->getThrowExceptions()) throw new \Exception($message);
                if ($this->getLogErrors()) self::logError($message);
            }
        }
    }

    /**
     * Returns:
     * - pickup corresponding to given delivery (if given point is of delivery type)
     * - delivery corresponding to given pickup (if given point is of pickup type)
     *
     * @param  array $allPoints
     * @param  \Litvinenko\Combinatorics\Pdp\Point $targetPoint
     *
     * @return \Litvinenko\Combinatorics\Pdp\Point|null
     */
    public static function getComplementaryPdpPoint(array $allPoints, \Litvinenko\Combinatorics\Pdp\Point $targetPoint)
    {
        $result = null;

        $halfPointCount = count($allPoints) / 2;
        foreach ($allPoints as $point)
        {
            if (
                $targetPoint->isPickup() && $point->isDelivery() && ((int)$point->getId() == (int)$targetPoint->getId() + $halfPointCount)
                ||
                $targetPoint->isDelivery() && $point->isPickup() && ((int)$point->getId() == (int)$targetPoint->getId() - $halfPointCount)
               )
            {
                $result = $point;
                break;
            }
        }

        return $result;
    }
}