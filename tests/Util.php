<?php

namespace Lacuna\Scanner\Tests;


use Exception;
use Lacuna\Scanner\ScannerClient;
use Lacuna\Scanner\ScannerOptions;
use PHPUnit\Framework\TestCase;

class Util
{
    public static function getClient()
    {
        if (empty(Config::getInstance()->apiKey)) {
            throw new \RuntimeException('The Scanner API key was not set on config.json file.');
        }
        $options = new ScannerOptions();
        $options->endpoint = Config::getInstance()->endpoint;
        $options->apiKey = Config::getInstance()->apiKey;
        return new ScannerClient($options);
    }

    public static function openBrowser($url)
    {
        $cmd = null;
        $osName = strtoupper(php_uname());
        if (strpos($osName, 'DARWIN') !== false) { // MacOS
            $cmd = "open $url &";
        } else if (strpos($osName, 'WIN') !== false) { // Windows
            $cmd = "start $url";
        } else { // Linux
            $cmd = "xdg-open $url &";
        }
        pclose(popen($cmd, 'r'));
    }
}