<?php


namespace Lacuna\Scanner;

/**
 * Interface ScannerServiceInterface
 * @package Lacuna\Scanner
 */
interface ScannerServiceInterface
{
    public function createScanSession($returnUrl, $multifile = false, $metadataPresets = null, $subscriptionId = null);
    public function getScanSession($scanSessionId);
    public function getDocumentDownloadLink($documentId);
    public function openReadDocument($documentId);
    public function getDocumentContent($documentId);
    public function getDocumentMetadataFileDownloadLink($documentId);
    public function openReadDocumentMetadataFile($documentId);
    public function getDocumentMetadataFileContent($documentId);
}