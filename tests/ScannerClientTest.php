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
            self::markTestSkipped('The variable \'scanSessionIdWithoutMultifile\' was not provided in \'config.json\' file');
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
            self::markTestSkipped('The variable \'scanSessionIdWithoutMultifile\' was not provided in \'config.json\' file');
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
            self::markTestSkipped('The variable \'scanSessionIdWithMultifile\' was not provided in \'config.json\' file');
        }
        $client = Util::getClient();
        $session = $client->getScanSession(Config::getInstance()->scanSessionIdWithMultifile);
        self::assertTrue($session->multifile);
        self::assertTrue($session->result == ScanSessionResults::SUCCESS);
        self::assertTrue(count($session->documents) > 0);
    }

    public function testGetDocument()
    {
        if (empty(Config::getInstance()->documentId)) {
            self::markTestSkipped('The variable \'documentId\' was not provided in \'config.json\' file');
        }
        $client = Util::getClient();
        $document = $client->getDocument(Config::getInstance()->documentId);
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/', $document->id);

        // Validate hash
        self::assertNotNull($document->hash);
        self::assertNotNull($document->hash->getHexValue());
        self::assertTrue(is_string($document->hash->getHexValue()));
        self::assertNotNull($document->hash->getValue());
        self::assertTrue(is_string($document->hash->getValue()));
        self::assertNotNull($document->hash->getAlgorithm());

        // Validate filename
        self::assertNotNull($document->fileName);
        self::assertTrue(is_string($document->fileName));

        // Validate contentLength
        self::assertNotNull($document->contentLength);
        self::assertTrue(is_int($document->contentLength));

        // Validate contentType
        self::assertNotNull($document->contentType);
        self::assertTrue(is_string($document->contentType));

        // Validate descriptiveMetadata
        self::assertNotNull($document->descriptiveMetadata);
        self::assertNotNull($document->descriptiveMetadata->title);
        self::assertTrue(is_string($document->descriptiveMetadata->title));
        self::assertNotNull($document->descriptiveMetadata->keywords);
        self::assertTrue(is_array($document->descriptiveMetadata->keywords));
        self::assertNotNull($document->descriptiveMetadata->creator);
        self::assertTrue(is_string($document->descriptiveMetadata->creator));
        self::assertNotNull($document->descriptiveMetadata->documentType);
        self::assertTrue(is_string($document->descriptiveMetadata->documentType));
        if (isset($document->descriptiveMetadata->dateCreated)) {
            self::assertTrue(is_string($document->descriptiveMetadata->dateCreated));
        }
        if (isset($document->descriptiveMetadata->locationCreated)) {
            self::assertTrue(is_string($document->descriptiveMetadata->locationCreated));
        }
        if (isset($document->descriptiveMetadata->classification)) {
            self::assertTrue(is_string($document->descriptiveMetadata->classification));
        }
        if (isset($document->descriptiveMetadata->destination)) {
            self::assertTrue(is_string($document->descriptiveMetadata->destination));
        }
        if (isset($document->descriptiveMetadata->genre)) {
            self::assertTrue(is_string($document->descriptiveMetadata->genre));
        }
        if (isset($document->descriptiveMetadata->storagePeriod)) {
            self::assertTrue(is_string($document->descriptiveMetadata->storagePeriod));
        }

        // Validate administrativeMetadata
        self::assertNotNull($document->administrativeMetadata);
        self::assertNotNull($document->administrativeMetadata->scannedByDomesticGovernment);
        self::assertTrue(is_bool($document->administrativeMetadata->scannedByDomesticGovernment));
        self::assertNotNull($document->administrativeMetadata->scanningPersonName);
        self::assertTrue(is_string($document->administrativeMetadata->scanningPersonName));
        self::assertNotNull($document->administrativeMetadata->scanningPersonCpf);
        self::assertTrue(is_string($document->administrativeMetadata->scanningPersonCpf));
        self::assertNotNull($document->administrativeMetadata->dateScanned);
        self::assertTrue(is_string($document->administrativeMetadata->dateScanned));
        self::assertNotNull($document->administrativeMetadata->locationScanned);
        self::assertTrue(is_string($document->administrativeMetadata->locationScanned));
        if (isset($document->administrativeMetadata->scanningEntityName)) {
            self::assertTrue(is_string($document->administrativeMetadata->scanningEntityName));
        }
        if (isset($document->administrativeMetadata->scanningEntityCnpj)) {
            self::assertTrue(is_string($document->administrativeMetadata->scanningEntityCnpj));
        }
    }

    public function testGetDocumentContent()
    {
        if (empty(Config::getInstance()->documentId)) {
            self::markTestSkipped('The variable \'documentId\' was not provided in \'config.json\' file');
        }
        $client = Util::getClient();
        $document = $client->getDocument(Config::getInstance()->documentId);
        $content = $client->getDocumentContent(Config::getInstance()->documentId);
        self::assertTrue($document->contentLength == strlen($content));
        file_put_contents('C:\\Temp\\scanner-file.pdf', $content);
    }

    public function testGetDocumentMetadataFileContent()
    {
        if (empty(Config::getInstance()->documentId)) {
            self::markTestSkipped('The variable \'documentId\' was not provided in \'config.json\' file');
        }
        $client = Util::getClient();
        $content = $client->getDocumentMetadataFileContent(Config::getInstance()->documentId);
        if (isset($content)) {
            file_put_contents('C:\\Temp\\scanner-file-metadata.xml', $content);
        }
        self::expectNotToPerformAssertions();
    }
}