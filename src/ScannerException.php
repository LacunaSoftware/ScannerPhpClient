<?php


namespace Lacuna\Scanner;

use Exception;

/**
 * Class ScannerException
 * @package Lacuna\Scanner
 *
 * The exception that occurs when requesting Scanner and some error has occurred
 * on server. It contains the information of this error.
 *
 * @property $errorCode string The code of error that occurred on Scanner.
 * @property $errorMessage string The explanation of the error that occurred on
 *           Scanner.
 */
class ScannerException extends RestException
{
    /**
     * @private
     * @var string
     *
     * The code of the error that occurred on Scanner.
     */
    private $_errorCode;

    /**
     * @private
     * @var string
     *
     * The explanation of the error that occurred on Scanner.
     */
    private $_errorMessage;

    /**
     * AmpliaException constructor.
     *
     * @param $verb string The HTTP method used at the request.
     * @param $url string The request URL.
     * @param $model mixed The error model provided by Scanner.
     * @param Exception|null $previous The exception that cause this exception
     *        emission.
     */
    public function __construct(
        $verb,
        $url,
        $model,
        \Exception $previous = null
    ) {
        $message = "Scanner API error {$model->code}: {$model->message}";
        parent::__construct($message, $verb, $url, $previous);
        $this->_errorCode = $model->code;
        $this->_errorMessage = $model->message;
    }

    /**
     * Gets the code of the error.
     *
     * @return string The error code.
     */
    public function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * Sets the code of the error.
     *
     * @param string $errorCode The error code.
     */
    public function setErrorCode($errorCode)
    {
        $this->_errorCode = $errorCode;
    }

    /**
     * Gets the message of the error.
     *
     * @return string The error message.
     */
    public function getErrorMessage()
    {
        return $this->_errorMessage;
    }

    /**
     * Sets the message of the error.
     *
     * @param string $errorMessage The error message.
     */
    public function setErrorMessage($errorMessage)
    {
        $this->_errorMessage = $errorMessage;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case 'errorCode':
                return $this->getErrorCode();
            case 'errorMessage':
                return $this->getErrorMessage();
            default:
                return parent::__get($prop);
        }
    }

    public function __isset($prop)
    {
        switch ($prop) {
            case 'errorCode':
                return isset($this->_errorCode);
            case 'errorMessage':
                return isset($this->_errorMessage);
            default:
                return parent::__isset($prop);
        }
    }

    public function __set($prop, $value)
    {
        switch ($prop) {
            case 'errorCode':
                $this->setErrorCode($value);
                break;
            case 'errorMessage':
                $this->setErrorMessage($value);
                break;
            default:
                parent::__set($prop, $value);
        }
    }
}