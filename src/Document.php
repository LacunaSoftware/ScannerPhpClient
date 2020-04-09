<?php


namespace Lacuna\Scanner;

/**
 * Class Document
 * @package Lacuna\Scanner
 */
class Document
{
    private $client;
    public $id;
    public $hash;
    public $fileName;
    public $contentLength;
    public $contentType;
    public $descriptiveMetadata;
    public $administrativeMetadata;

    /**
     * Document constructor.
     * @param AbstractScannerClient $client
     * @param $model
     */
    function __construct(AbstractScannerClient $client, $model)
    {
        $this->client = $client;
        $this->id = $model->id;
        $this->hash = new HashAlgorithmAndValue($model->hash);
        $this->fileName = $model->fileName;
        $this->contentLength = $model->contentLength;
        $this->contentType = $model->contentType;
        $this->descriptiveMetadata = $model->descriptiveMetadata;
        $this->administrativeMetadata = $model->administrativeMetadata;
    }

    /**
     * @param $path
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function writeToFile($path) {
        $this->client->writeDocumentToFile($this->id, $path);
    }

    /**
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getContentRaw() {
        return $this->client->getDocumentContent($this->id);
    }

    /**
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getContentBase64() {
        return base64_encode($this->getContentRaw());
    }

    /**
     * @param $path
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function writeMetadataToFile($path) {
        $this->client->writeDocumentMetadataToFile($this->id, $path);
    }

    /**
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getMetadataFileContentRaw() {
        return $this->client->getDocumentMetadataFileContent($this->id);
    }

    /**
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getMetadataFileContentBase64() {
        return base64_encode($this->getMetadataFileContentRaw());
    }

}