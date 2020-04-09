<?php


namespace Lacuna\Scanner;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use UnexpectedValueException;

/**
 * Class RestClient
 * @package Lacuna\Scanner
 *
 * @property $endpointUri string
 * @property $apiKey string
 * @property $customRequestHeaders array
 * @property $usePhpCAInfo bool
 * @property $caInfoPath string
 */
class RestClient
{
    /**
     * @private
     * @var string
     */
    private $_endpointUri;

    /**
     * @private
     * @var string
     */
    private $_apiKey;

    /**
     * @private
     * @var array
     */
    private $_customRequestHeaders;

    /**
     * @private
     * @var bool
     */
    private $_usePhpCAInfo;

    /**
     * @private
     * @var string
     */
    private $_caInfoPath;

    /**
     * RestClient constructor.
     * @param $endpointUri
     * @param $apiKey
     * @param array $customRequestHeaders
     * @param bool $usePhpCAInfo
     * @param string|null $caInfoPath
     */
    public function __construct(
        $endpointUri,
        $apiKey,
        $customRequestHeaders = [],
        $usePhpCAInfo = false,
        $caInfoPath = null
    ) {
        $this->_endpointUri = $endpointUri;
        $this->_apiKey = $apiKey;
        $this->_customRequestHeaders = $customRequestHeaders;
        $this->_usePhpCAInfo = $usePhpCAInfo;

        if (!isset($caInfoPath)) {
            $caInfoPath = __DIR__ . '/../resources/ca-bundle.pem';
        }
        $this->_caInfoPath = $caInfoPath;
    }

    /**
     * @param $url
     * @return HttpResponse
     * @throws ScannerException
     * @throws RestErrorException
     * @throws RestUnreachableException
     */
    public function get($url)
    {
        $verb = 'GET';
        $client = $this->_getClient();
        $httpResponse = null;
        try {
            $httpResponse = $client->get($url);
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->_checkResponse($verb, $url, $httpResponse);
        return HttpResponse::getInstance($httpResponse);
    }

    /**
     * @param $url
     * @param $data
     * @return HttpResponse
     * @throws ScannerException
     * @throws RestErrorException
     * @throws RestUnreachableException
     */
    public function post($url, $data)
    {
        $verb = 'POST';
        $client = $this->_getClient();
        $httpResponse = null;
        try {
            if (empty($data)) {
                $httpResponse = $client->post($url);
            } else {
                $httpResponse = $client->post($url, array('json' => $data));
            }
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->_checkResponse($verb, $url, $httpResponse);
        return HttpResponse::getInstance($httpResponse);
    }

    /**
     * @param $url
     * @return HttpResponse
     * @throws ScannerException
     * @throws RestErrorException
     * @throws RestUnreachableException
     *
     */
    public function delete($url)
    {
        $verb = 'DELETE';
        $client = $this->_getClient();
        $httpResponse = null;
        try {
            $httpResponse = $client->delete($url);
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->_checkResponse($verb, $url, $httpResponse);
        return HttpResponse::getInstance($httpResponse);
    }

    /**
     * @internal
     *
     * @param $url
     * @return mixed
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function downloadContent($url)
    {
        $verb = 'GET';
        $client = $this->_getClient();
        $httpResponse = null;
        try {
            $httpResponse = $client->get($url);
        } catch (TransferException $ex) {
            throw new RestUnreachableException($verb, $url, $ex);
        }
        $this->_checkResponse($verb, $url, $httpResponse);
        return HttpResponse::getInstance($httpResponse);
    }

    /**
     * @internal
     *
     * @param $url
     * @param $path
     * @throws RestErrorException
     * @throws RestUnreachableException
     * @throws ScannerException
     */
    public function downloadToFile($url, $path)
    {
        $handle = fopen($path, 'wb');

        try {

            $verb = 'GET';
            $client = $this->_getClient();
            $httpResponse = null;
            try {
                $httpResponse = $client->get($url, ['sink' => $handle]);
            } catch (TransferException $ex) {
                throw new RestUnreachableException($verb, $url, $ex);
            }
            $this->_checkResponse($verb, $url, $httpResponse);

        } finally {
            fclose($handle);
        }
    }

    /**
     * @private
     * @return Client
     */
    private function _getClient()
    {
        $headers = [
            'Accept' => 'application/json'
        ];
        if (!empty($this->apiKey)) {
            $headers['X-Api-Key'] = $this->apiKey;
        }
        foreach ($this->_customRequestHeaders as $key => $value) {
            $headers[$key] = $value;
        }

        $verify = true;
        if (!$this->_usePhpCAInfo) {
            if (!isset($this->_caInfoPath)) {
                throw new UnexpectedValueException('No CA certificates path was provided. Set the "usePhpCAInfo" variable to true if you want to use the default value that your PHP uses.');
            }
            if (!file_exists($this->_caInfoPath)) {
                throw new InvalidArgumentException("The provided cacert file does not exist: {$this->_caInfoPath}.");
            }
            $verify = $this->_caInfoPath;
        }

        return new Client([
            'base_uri' => $this->_endpointUri,
            'headers' => $headers,
            'http_errors' => false,
            'verify' => $verify
        ]);
    }

    /**
     * @private
     * @param $verb string
     * @param $url string
     * @param $httpResponse ResponseInterface
     * @throws ScannerException
     * @throws RestErrorException
     */
    private function _checkResponse($verb, $url, $httpResponse)
    {
        $statusCode = $httpResponse->getStatusCode();
        if ($statusCode < 200 || $statusCode > 299) {
            $ex = null;
            try {
                $response = Util::decodeJson($httpResponse->getBody());
                if ($statusCode == 422 && isset($response->code)) {
                    $ex = new ScannerException($verb, $url, $response);
                } else {
                    $ex = new RestErrorException($verb, $url, $statusCode,
                        $response->message);
                }
            } catch (Exception $e) {
                $ex = new RestErrorException($verb, $url, $statusCode);
            }
            throw $ex;
        }
    }

    /**
     * @return string
     */
    public function getEndpointUri()
    {
        return $this->_endpointUri;
    }

    /**
     * @param string $endpointUri
     */
    public function setEndpointUri($endpointUri)
    {
        $this->_endpointUri = $endpointUri;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->_apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->_apiKey = $apiKey;
    }

    /**
     * @return array
     */
    public function getCustomRequestHeaders()
    {
        return $this->_customRequestHeaders;
    }

    /**
     * @param array $customRequestHeaders
     */
    public function setCustomRequestHeaders($customRequestHeaders)
    {
        $this->_customRequestHeaders = $customRequestHeaders;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addCustomRequestHeaders($key, $value)
    {
        $this->_customRequestHeaders[$key] = $value;
    }

    /**
     * @param $key
     */
    public function removeCustomRequestHeaders($key)
    {
        if (isset($this->_customRequestHeaders[$key])) {
            unset($this->_customRequestHeaders[$key]);
        }
    }

    /**
     * @return bool
     */
    public function getUsePhpCAInfo()
    {
        return $this->_usePhpCAInfo;
    }

    /**
     * @param bool $usePhpCAInfo
     */
    public function setUsePhpCAInfo($usePhpCAInfo)
    {
        $this->_usePhpCAInfo = $usePhpCAInfo;
    }

    /**
     * @return string
     */
    public function getCAInfoPath()
    {
        return $this->_caInfoPath;
    }

    /**
     * @param string $caInfoPath
     */
    public function setCAInfoPath($caInfoPath)
    {
        $this->_caInfoPath = $caInfoPath;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case 'endpointUri':
                return $this->getEndpointUri();
            case 'apiKey':
                return $this->getApiKey();
            case 'customRequestHeaders':
                return $this->getCustomRequestHeaders();
            case 'usePhpCAInfo':
                return $this->getUsePhpCAInfo();
            case 'caInfoPath':
                return $this->getCAInfoPath();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
                return null;
        }
    }

    public function __isset($prop)
    {
        switch ($prop) {
            case 'endpointUri':
                return isset($this->_endpointUri);
            case 'apiKey':
                return isset($this->_apiKey);
            case 'customRequestHeaders':
                return isset($this->_customRequestHeaders);
            case 'usePhpCAInfo':
                return isset($this->_usePhpCAInfo);
            case 'caInfoPath':
                return isset($this->_caInfoPath);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
                return false;
        }
    }

    public function __set($prop, $value)
    {
        switch ($prop) {
            case 'endpointUri':
                $this->setEndpointUri($value);
                break;
            case 'apiKey':
                $this->setApiKey($value);
                break;
            case 'customRequestHeaders':
                $this->setCustomRequestHeaders($value);
                break;
            case 'usePhpCAInfo':
                $this->setUsePhpCAInfo($value);
                break;
            case 'caInfoPath':
                $this->setCAInfoPath($value);
                break;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
        }
    }
}