<?php
class Pdp_Helper
{
    public static function getDistanceBetween($firstPoint, $secondPoint)
    {
        return sqrt(pow($firstPoint.x - $secondPoint.x,2) + pow($firstPoint.y - $secondPoint.y,2));
    }
}
