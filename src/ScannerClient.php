<?php


namespace Lacuna\Scanner;

/**
 * Class ScannerClient
 * @package Lacuna\Scanner
 */
class ScannerClient implements ScannerServiceInterface
{
    protected $_restClient;
    private $options;

    /**
     * ScannerClient constructor.
     * @param ScannerOptions $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    // region Scan Sessions

    /**
     * @param string $returnUrl
     * @param bool $multifile
     * @param MetadataPresets $metadataPresets
     * @param string $subscriptionId
     * @return CreateScanSessionResponse
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function createScanSession($returnUrl, $multifile = false, $metadataPresets = null, $subscriptionId = null)
    {
        $request = [
            'returnUrl' => $returnUrl,
            'multifile' => $multifile,
            'metadataPresets' => $metadataPresets,
        ];
        $customHeaders = [];
        if (isset($subscriptionId)) {
            $customHeaders['X-Subscription'] = $subscriptionId;
        }
        $client = $this->_getRestClient($customHeaders);

        $response = $client->post('/api/scan-sessions', $request);
        return new CreateScanSessionResponse($response->getBodyAsJson());
    }

    /**
     * @param string $scanSessionId
     * @return ScanSession
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getScanSession($scanSessionId)
    {
        $client = $this->_getRestClient();
        $response = $client->get("/api/scan-sessions/$scanSessionId");
        return new ScanSession($this, $response->getBodyAsJson());
    }

    // endregion

    // region Documents

    /**
     * @param string $documentId
     * @return Document
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocument($documentId)
    {
        $client = $this->_getRestClient();
        $response = $client->get("/api/documents/$documentId");
        return new Document($this, $response->getBodyAsJson());
    }

    /**
     * @param $documentId
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocumentDownloadLink($documentId)
    {
        $client = $this->_getRestClient();
        $response = $client->get("/api/documents/$documentId/file-link");
        return $this->processLink((string) $response->body);
    }

    /**
     * @param $documentId
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function openReadDocument($documentId)
    {
        $client = $this->_getRestClient();
        $downloadLink = $this->getDocumentDownloadLink($documentId);
        return $client->openStream($downloadLink);
    }

    /**
     * @param string $documentId
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocumentContent($documentId)
    {
        $stream = $this->openReadDocument($documentId);
        return $stream->getContents();
    }

    /**
     * @param $documentId
     * @return string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocumentMetadataFileDownloadLink($documentId)
    {
        $client = $this->_getRestClient();
        $response = $client->get("/api/documents/$documentId/metadata-file-link");
        return $this->processLink((string) $response->body);
    }

    /**
     * @param $documentId
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function openReadDocumentMetadataFile($documentId)
    {
        $client = $this->_getRestClient();
        $downloadLink = $this->getDocumentMetadataFileDownloadLink($documentId);
        return $client->openStream($downloadLink);
    }

    /**
     * @param $documentId
     * @return false|string
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocumentMetadataFileContent($documentId)
    {
        $stream = $this->openReadDocumentMetadataFile($documentId);
        return $stream->getContents();
    }

    // endregion

    /**
     * @protected
     *
     * Gets an client to perform the HTTP requests.
     *
     * @param $customRequestHeaders array
     * @return RestClient The REST client used to perform the HTTP requests.
     */
    protected function _getRestClient($customRequestHeaders = [])
    {
        if (!isset($this->_restClient)) {
            $this->_restClient = new RestClient(
                $this->options->endpoint,
                $this->options->apiKey,
                $customRequestHeaders,
                $this->options->usePhpCAInfo,
                $this->options->caInfoPath
            );
        }
        if (!empty($customRequestHeaders)) {
            $this->_restClient->customRequestHeaders = $customRequestHeaders;
        }
        return $this->_restClient;
    }

    /**
     * @param $url string
     * @return string
     */
    private function processLink($url)
    {
        $trim = trim($url);
        if ($trim[0] == '"') {
            $trim = substr($trim, 1);
        }
        if ($trim[strlen($trim)-1] == '"') {
            $trim = substr($trim, 0, strlen($trim) - 1);
        }
        return $trim;
    }
}