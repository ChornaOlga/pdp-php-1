<?php
require 'vendor/autoload.php';

class User extends Litvinenko\Common\Object
{
    const A = 'a';
    protected $dataRules;

    public function _construct()
    {
        $this->dataRules = array(
            // 'login'   => 'required|in:'.self::A.',b,c',
            // 'email'   => 'required|email',
            'user_id' => 'required|integer_strict|less_than:2',
        );
    }
}

$user = new User([
    // 'login'   => 'c',
    // 'email'   => 'some_email@gmail.com',
    'user_id' => 3,
]);

echo ($user->isValid()) ? "User is valid\n" : "User is invalid\n";
print_r($user->getValidationErrors());