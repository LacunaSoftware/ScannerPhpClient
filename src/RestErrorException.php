<?php


namespace Lacuna\Scanner;


/**
 * Class RestErrorException
 * @package Lacuna\Scanner
 *
 * @property $statusCode int
 * @property $errorMessage string
 */
class RestErrorException extends RestException
{
    /**
     * @private
     * @var int
     */
    private $_statusCode;

    /**
     * @private
     * @var string
     */
    private $_errorMessage;

    /**
     * RestErrorException constructor.
     *
     * @param string $verb
     * @param string $url
     * @param int $statusCode
     * @param string $errorMessage
     */
    public function __construct($verb, $url, $statusCode, $errorMessage = null)
    {
        $message = "REST action {$verb} {$url} returned HTTP error {$statusCode}";
        if (isset($errorMessage)) {
            $message .= ": {$errorMessage}";
        }
        parent::__construct($message, $verb, $url);
        $this->_statusCode = $statusCode;
        $this->_errorMessage = $errorMessage;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->_statusCode = $statusCode;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * @param string $errorMessage
     */
    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case 'statusCode':
                return $this->getStatusCode();
            case 'errorMessage':
                return $this->getErrorMessage();
            default:
                return parent::__get($prop);
        }
    }

    public function __isset($prop)
    {
        switch ($prop) {
            case 'statusCode':
                return isset($this->_statusCode);
            case 'errorMessage':
                return isset($this->_errorMessage);
            default:
                return parent::__isset($prop);
        }
    }

    public function __set($prop, $value)
    {
        switch ($prop) {
            case 'statusCode':
                $this->setStatusCode($value);
                break;
            case 'errorMessage':
                $this->setErrorMessage($value);
                break;
            default:
                parent::__set($prop, $value);
        }
    }
}