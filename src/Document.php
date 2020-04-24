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
     * @param ScannerClient $client
     * @param $model
     */
    function __construct(ScannerClient $client, $model)
    {
        $this->client = $client;
        $this->id = $model->id;
        $this->fileName = $model->fileName;
        $this->contentLength = $model->contentLength;
        $this->contentType = $model->contentType;

        if (isset($model->hash)) {
            $this->hash = new DigestAlgorithmAndValue($model->hash);
        }
        if (isset($model->descriptiveMetadata)) {
            $this->descriptiveMetadata = new DescriptiveMetadata($model->descriptiveMetadata);
        }
        if (isset($model->administrativeMetadata)) {
            $this->administrativeMetadata = new AdministrativeMetadata($model->administrativeMetadata);
        }
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