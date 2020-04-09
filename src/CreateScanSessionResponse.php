<?php


namespace Lacuna\Scanner;

/**
 * Class CreateScanSessionResponse
 * @package Lacuna\Scanner
 */
class CreateScanSessionResponse
{
    public $scanSessionId;
    public $redirectUrl;

    public function __construct($model)
    {
        $this->scanSessionId = $model->scanSessionId;
        $this->redirectUrl = $model->redirectUrl;
    }
}