<?php

namespace Lacuna\Scanner\Tests;


use Lacuna\Scanner\ScanSessionResults;
use PHPUnit\Framework\TestCase;

class ScannerClientTest extends TestCase
{
    const RETURN_URL = 'https://www.patorum.com.br';

    public function testCreateScanSessionWithoutMultifile()
    {
        $client = Util::getClient();
        $response = $client->createScanSession(self::RETURN_URL, false);

        $endpoint = Config::getInstance()->endpoint;
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/', $response->scanSessionId);
        self::assertMatchesRegularExpression("#${endpoint}/scan/.*#", $response->redirectUrl);

        if (Config::getInstance()->openBrowser) {
            Util::openBrowser($response->redirectUrl);
        }
    }

    public function testCreateScanSessionWithMultifile()
    {
        $client = Util::getClient();
        $response = $client->createScanSession(self::RETURN_URL, true);

        $endpoint = Config::getInstance()->endpoint;
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/', $response->scanSessionId);
        self::assertMatchesRegularExpression("#${endpoint}/scan/.*#", $response->redirectUrl);

        if (Config::getInstance()->openBrowser) {
            Util::openBrowser($response->redirectUrl);
        }
    }

    public function testGetScanSessionNotCompleted()
    {
        $multifile = false;
        $client = Util::getClient();
        $createResponse = $client->createScanSession(self::RETURN_URL, $multifile);
        $session = $client->getScanSession($createResponse->scanSessionId);
        self::assertTrue($session->id == $createResponse->scanSessionId);
        self::assertTrue($session->multifile == $multifile);
        self::assertNull($session->result);
        self::assertEmpty($session->documents);
    }

    public function testGetScanSessionCancelled()
    {
        if (empty(Config::getInstance()->scanSessionIdCancelled)) {
            self::markTestSkipped('The variable \'scanSessionIdWithoutMultifile\' was not provided on \'config.json\' file');
        }
        $client = Util::getClient();
        $session = $client->getScanSession(Config::getInstance()->scanSessionIdCancelled);
        self::assertTrue($session->id == Config::getInstance()->scanSessionIdCancelled);
        self::assertTrue($session->result == ScanSessionResults::USER_CANCELLED);
        self::assertEmpty($session->documents);
    }

    public function testGetScanSessionWithoutMultifile()
    {
        if (empty(Config::getInstance()->scanSessionIdWithoutMultifile)) {
            self::markTestSkipped('The variable \'scanSessionIdWithoutMultifile\' was not provided on \'config.json\' file');
        }
        $client = Util::getClient();
        $session = $client->getScanSession(Config::getInstance()->scanSessionIdWithoutMultifile);
        self::assertFalse($session->multifile);
        self::assertTrue($session->result == ScanSessionResults::SUCCESS);
        self::assertTrue(count($session->documents) == 1);
    }

    public function testGetScanSessionWithMultifile()
    {
        if (empty(Config::getInstance()->scanSessionIdWithMultifile)) {
            self::markTestSkipped('The variable \'scanSessionIdWithMultifile\' was not provided on \'config.json\' file');
        }
        $client = Util::getClient();
        $session = $client->getScanSession(Config::getInstance()->scanSessionIdWithMultifile);
        self::assertTrue($session->multifile);
        self::assertTrue($session->result == ScanSessionResults::SUCCESS);
        self::assertTrue(count($session->documents) > 0);
    }
}