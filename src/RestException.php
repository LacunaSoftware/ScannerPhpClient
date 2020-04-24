<?php


namespace Lacuna\Scanner;

use Exception;

/**
 * Class RestException
 * @package Lacuna\Scanner
 *
 * @property $verb string
 * @property $url string
 */
class RestException extends Exception
{
    /**
     * @private
     * @var string
     */
    private $_verb;

    /**
     * @private
     * @var string
     */
    private $_url;

    /**
     * RestException constructor.
     *
     * @param $message
     * @param $verb
     * @param $url
     * @param Exception|null $previous
     */
    public function __construct(
        $message,
        $verb,
        $url,
        Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->_verb = $verb;
        $this->_url = $url;
    }

    /**
     * @return string
     */
    public function getVerb()
    {
        return $this->_verb;
    }

    /**
     * @param string $verb
     */
    public function setVerb($verb)
    {
        $this->_verb = $verb;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->_url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->_url = $url;
    }

    public function __get($prop)
    {
        switch ($prop) {
            case 'verb':
                return $this->getVerb();
            case 'url':
                return $this->getUrl();
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
                return null;
        }
    }

    public function __isset($prop)
    {
        switch ($prop) {
            case 'verb':
                return isset($this->_verb);
            case 'url':
                return isset($this->_url);
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
                return false;
        }
    }

    public function __set($prop, $value)
    {
        switch ($prop) {
            case 'verb':
                $this->setVerb($value);
                break;
            case 'url':
                $this->setUrl($value);
                break;
            default:
                trigger_error('Undefined property: ' . __CLASS__ . '::$' . $prop);
        }
    }
}