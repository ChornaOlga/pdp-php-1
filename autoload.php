<?php

function __autoload($class)
{
    $pathParams  = explode('_', $class);
    $paramsCount = count($pathParams);

    if ($paramsCount == 1)
    {
        $dir      = '';
        $filename = $class;
    }
    else
    {
        $dir      = implode('/', array_slice($pathParams, 0, $paramsCount - 1)). '/';
        $filename = end($pathParams);
    }

    require_once('classes/' . $dir . $filename .'.php');
}