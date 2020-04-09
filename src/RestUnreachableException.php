<?php


namespace Lacuna\Scanner;

use Exception;

/**
 * Class RestUnreachableException
 * @package Lacuna\Scanner
 */
class RestUnreachableException extends RestException
{
    /**
     * RestUnreachableException constructor.
     * @param $verb
     * @param $url
     * @param Exception|null $previous
     */
    public function __construct($verb, $url, \Exception $previous = null)
    {
        parent::__construct("REST action {$verb} {$url} unreachable", $verb,
            $url, $previous);
    }
}