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
    public function writeDocumentToFile($documentId, $path);
    public function getDocumentContent($documentId);
    public function writeDocumentMetadataToFile($documentId, $path);
    public function getDocumentMetadataFileContent($documentId);
}