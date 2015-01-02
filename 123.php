<?php
class Father
{
    public static $necessaryData=['a','b','c'];
    public function __construct($data)
    {
        $this->validateNecessaryData($data, self::$necessaryData);
    }

    protected function validateNecessaryData($actualData, $necessaryData)
    {
        $absentParams    = [];
        $actualParams    = array_keys($actualData);
        $necessaryParams = $necessaryData;

            var_dump($actualParams);
            var_dump($necessaryParams);
        foreach ($necessaryParams as $necessaryParam)
        {
            if (!in_array( $necessaryParam, $actualParams))
            {
                $absentParams[] = $necessaryParam;
            }
        }

var_dump($absentParams);
        throw new Exception ("Class ". get_class($this) . " can't initialize due to missing necessary data: " . implode(', ', $absentParams));
        exit;
    }
}

class Son extends Father
{
    // public static $necessaryData=['d','e','f'];
    public function __construct($data)
    {
        // $this->validateNecessaryData($data, self::$necessaryData);
        // $this->necessaryData = array_merge(parent::$necessaryData, $necessaryData);
        self::$necessaryData = array_merge(parent::$necessaryData, [
            'd',
            'e',
            'f'
        ]);
        parent::__construct($data);
    }
}

new Son(['a' => 1, 'd' => 1, 'e' => 1, 'f' => 1]);