<?php


namespace Lacuna\Scanner;

/**
 * Class ScanSession
 * @package Lacuna\Scanner
 *
 * @property string $id
 * @property bool $multifile
 * @property string $result
 * @property array $documents
 */
class ScanSession
{
    private $client;

    public $id;
    public $multifile;
    public $result;
    public $documents;

    /**
     * ScanSession constructor.
     * @param ScannerClient $client
     * @param $model
     */
    function __construct(ScannerClient $client, $model)
    {
        $this->client = $client;
        $this->id = $model->id;
        $this->multifile = $model->multifile;
        $this->result = $model->result;
        $this->documents = array_map(function($d) { return new Document($this->client, $d); }, $model->documents);
    }
}