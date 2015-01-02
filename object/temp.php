<?php
// class Father
// {
//     public static $necessaryData=['a','b','c'];
//     public function __construct($data)
//     {
//         $this->validateNecessaryData($data, self::$necessaryData);
//     }

//     protected function validateNecessaryData($actualData, $necessaryData)
//     {
//         $absentParams    = [];
//         $actualParams    = array_keys($actualData);
//         $necessaryParams = $necessaryData;

//             var_dump($actualParams);
//             var_dump($necessaryParams);
//         foreach ($necessaryParams as $necessaryParam)
//         {
//             if (!in_array( $necessaryParam, $actualParams))
//             {
//                 $absentParams[] = $necessaryParam;
//             }
//         }

// var_dump($absentParams);
//         throw new Exception ("Class ". get_class($this) . " can't initialize due to missing necessary data: " . implode(', ', $absentParams));
//         exit;
//     }
// }

// class Son extends Father
// {
//     // public static $necessaryData=['d','e','f'];
//     public function __construct($data)
//     {
//         // $this->validateNecessaryData($data, self::$necessaryData);
//         // $this->necessaryData = array_merge(parent::$necessaryData, $necessaryData);
//         self::$necessaryData = array_merge(parent::$necessaryData, [
//             'd',
//             'e',
//             'f'
//         ]);
//         parent::__construct($data);
//     }
// }

// new Son(['a' => 1, 'd' => 1, 'e' => 1, 'f' => 1]);
require 'vendor/autoload.php';

use Symfony\Component\Translation\Translator;
use Illuminate\Validation\Factory;

class Validator {

    protected static $factory;

    public static function instance()
    {
        if ( ! static::$factory)
        {
            $translator = new Symfony\Component\Translation\Translator('en');
            static::$factory = new Illuminate\Validation\Factory($translator);
        }

        return static::$factory;
    }

    public static function __callStatic($method, $args)
    {
        $instance = static::instance();

        switch (count($args))
        {
            case 0:
            return $instance->$method();

            case 1:
            return $instance->$method($args[0]);

            case 2:
            return $instance->$method($args[0], $args[1]);

            case 3:
            return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
            return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
            return call_user_func_array(array($instance, $method), $args);
        }
    }
}

$rules = [
'title'   => 'required|digits_between:3,255',
'email'    => 'required|email',
'user_id' => 'integer',
];

$data = [
'title'   => 1,
'user_id' => 'asd13',
'email' =>'2131'
];

// //print_r(get_declared_classes());
$validator = Validator::make($data, $rules, Litvinenko\Combinatorics\Common\Lang\ErrorMessages::getErrorMessages());
if ($validator->fails())
{
   echo "Class  can't initialize due to wrong data: \n" ;
   var_dump($validator->errors()->toArray());
   /*get_class($this)*/
}
