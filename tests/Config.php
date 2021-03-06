<?php

namespace Lacuna\Scanner\Tests;


use http\Exception\RuntimeException;

class Config
{
    private static $instance = null;

    public $endpoint;
    public $apiKey;
    public $openBrowser;
    public $scanSessionIdCancelled;
    public $scanSessionIdWithMultifile;
    public $scanSessionIdWithoutMultifile;
    public $documentId;

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $configPath = __DIR__ . "\\config.json";
            if (!file_exists($configPath)) {
                throw new \RuntimeException('The file \'config.json\' was not found');
            }
            $config = json_decode(file_get_contents($configPath));
            if ($config == false) {
                throw new \RuntimeException('The file \'config.json\' is not a valid JSON');
            }

            self::$instance = new Config();
            if (isset($config->endpoint)) {
                self::$instance->endpoint = $config->endpoint;
            } else {
                self::$instance->endpoint = 'https://scn.lacunasoftware.com';
            }

            if (empty($config->apiKey)) {
                throw new \RuntimeException('The variable \'apiKey\' was not provided in \'config.json\' file');
            }
            self::$instance->apiKey = $config->apiKey;

            self::$instance->openBrowser = $config->openBrowser;
            self::$instance->scanSessionIdCancelled = $config->scanSessionIdCancelled;
            self::$instance->scanSessionIdWithMultifile = $config->scanSessionIdWithMultifile;
            self::$instance->scanSessionIdWithoutMultifile = $config->scanSessionIdWithoutMultifile;
            self::$instance->documentId = $config->documentId;
        }
        return self::$instance;
    }
}