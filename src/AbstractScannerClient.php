<?php


namespace Lacuna\Scanner;

/**
 * Class AbstractScannerClient
 * @package Lacuna\Scanner
 */
abstract class AbstractScannerClient implements ScannerServiceInterface
{
    protected $httpClient;
    private $options;

    /**
     * AbstractScannerClient constructor.
     * @param AbstractScannerOptions $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    // region Scan Sessions

    /**
     * @param $returnUrl
     * @param bool $multifile
     * @param null $metadataPresets
     * @param null $subscriptionId
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
        return new CreateScanSessionResponse($response->body);
    }

    /**
     * @param $scanSessionId
     * @return ScanSession
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getScanSession($scanSessionId)
    {
        $client = $this->_getRestClient();
        $response = $client->get("/api/scan-sessions/$scanSessionId");
        return new ScanSession($this, $response->body);
    }

    // endregion

    // region Documents

    /**
     * @param $documentId
     * @return Document
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocument($documentId)
    {
        $client = $this->_getRestClient();
        $response = $client->get("/api/documents/$documentId");
        return new Document($this, $response->body);
    }

    /**
     * @param $documentId
     * @param $path
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function writeDocumentToFile($documentId, $path)
    {
        $client = $this->_getRestClient();
        $client->downloadToFile("/api/documents/$documentId/file-link", $path);
    }

    /**
     * @param $documentId
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocumentContent($documentId)
    {
        $client = $this->_getRestClient();
        $response = $client->downloadContent("/api/documents/$documentId/file-content");
        return $response->body;
    }

    /**
     * @param $documentId
     * @param $path
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function writeDocumentMetadataToFile($documentId, $path)
    {
        $client = $this->_getRestClient();
        $client->downloadToFile("/api/documents/$documentId/metadata-file-link", $path);
    }

    /**
     * @param $documentId
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function getDocumentMetadataFileContent($documentId)
    {
        $client = $this->_getRestClient();
        $response = $client->downloadContent("/api/documents/$documentId/metadata-content");
        return $response->body;
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
}