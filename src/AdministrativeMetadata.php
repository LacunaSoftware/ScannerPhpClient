<?php


namespace Lacuna\Scanner;

/**
 * Class AdministrativeMetadata
 * @package Lacuna\Scanner
 *
 * @property bool $scannedByDomesticGovernment
 * @property string $scanningPersonName
 * @property string $scanningPersonCpf
 * @property string $scanningEntityName
 * @property string $scanningEntityCnpj
 * @property string $dateScanned
 * @property string $locationScanned
 */
class AdministrativeMetadata
{
    public $scannedByDomesticGovernment;
    public $scanningPersonName;
    public $scanningPersonCpf;
    public $scanningEntityName;
    public $scanningEntityCnpj;
    public $dateScanned;
    public $locationScanned;

    public function __construct($model)
    {
        $this->scannedByDomesticGovernment = $model->scannedByDomesticGovernment;
        $this->scanningPersonName = $model->scanningPersonName;
        $this->scanningPersonCpf = $model->scanningPersonCpf;
        $this->scanningEntityName = $model->scanningEntityName;
        $this->scanningEntityCnpj = $model->scanningEntityCnpj;
        $this->dateScanned = $model->dateScanned;
        $this->locationScanned = $model->locationScanned;
    }
}