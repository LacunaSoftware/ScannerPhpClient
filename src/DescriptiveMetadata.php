<?php


namespace Lacuna\Scanner;

/**
 * Class DescriptiveMetadata
 * @package Lacuna\Scanner
 *
 * @property string $title
 * @property array $keywords
 * @property string $creator
 * @property string $dateCreated
 * @property string $locationCreated
 * @property string $classification
 * @property string $documentType
 * @property string $destination
 * @property string $genre
 * @property string $storagePeriod
 */
class DescriptiveMetadata
{
    public $title;
    public $keywords;
    public $creator;
    public $dateCreated;
    public $locationCreated;
    public $classification;
    public $documentType;
    public $destination;
    public $genre;
    public $storagePeriod;

    public function __construct($model)
    {
        $this->title = $model->title;
        $this->keywords = $model->keywords;
        $this->creator = $model->creator;
        $this->dateCreated = $model->dateCreated;
        $this->locationCreated = $model->locationCreated;
        $this->classification = $model->classification;
        $this->documentType = $model->documentType;
        $this->destination = $model->destination;
        $this->genre = $model->genre;
        $this->storagePeriod = $model->storagePeriod;
    }
}