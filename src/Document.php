<?php


namespace Lacuna\Scanner;

use Psr\Http\Message\StreamInterface;

/**
 * Class Document
 * @package Lacuna\Scanner
 *
 * @property string $id
 * @property DigestAlgorithmAndValue $hash
 * @property string $fileName
 * @property int $contentLength
 * @property string $contentType
 * @property DescriptiveMetadata $descriptiveMetadata
 * @property AdministrativeMetadata $administrativeMetadata
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
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDownloadLink()
    {
        return $this->client->getDocumentDownloadLink($this->id);
    }

    /**
     * @return StreamInterface
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function openRead()
    {
        return $this->client->openReadDocument($this->id);
    }

    /**
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getContent() {
        return $this->client->getDocumentContent($this->id);
    }

    /**
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getMetadataFileDownloadLink()
    {
        return $this->client->getDocumentMetadataFileDownloadLink($this->id);
    }

    /**
     * @return StreamInterface
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function openReadMetadataFile()
    {
        return $this->client->openReadDocumentMetadataFile($this->id);
    }

    /**
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getMetadataFileContent()
    {
        return $this->client->getDocumentMetadataFileContent($this->id);
    }
}