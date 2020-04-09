<?php


namespace Lacuna\Scanner;


class ScanSession
{
    private $client;

    public $id;
    public $multifile;
    public $result;
    public $documents;

    /**
     * @internal
     *
     * ScanSession constructor.
     * @param AbstractScannerClient $client
     * @param $model
     */
    function __construct(AbstractScannerClient $client, $model)
    {
        $this->client = $client;
        $this->id = $model->id;
        $this->multifile = $model->multifile;
        $this->result = $model->result;
        $this->documents = array_map(function($d) { return new Document($this->client, $d); }, $model->documents);
    }
}