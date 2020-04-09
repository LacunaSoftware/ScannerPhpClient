<?php


namespace Lacuna\Scanner;

/**
 * @internal
 *
 * Class Util
 * @package Lacuna\Scanner
 */
class Util
{
    /**
     * @internal
     *
     * @param $obj
     * @return false|string
     */
    static function encodeJson($obj)
    {
        return json_encode($obj);
    }

    /**
     * @internal
     *
     * @param $json
     * @return mixed
     */
    static function decodeJson($json)
    {
        return json_decode($json);
    }
}