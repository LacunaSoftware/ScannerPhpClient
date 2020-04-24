<?php


namespace Lacuna\Scanner;



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