<?php
// download.php
require_once '../vendor/autoload.php';

#use Librarian\FileSystem;
use Librarian\Configuration;
use Librarian\ConfigurationKey;
use Librarian\FileSystem;

$config = Configuration::getInstance();
$docsDir = $config->get(ConfigurationKey::DocsPath);
$fs = FileSystem::factory();

#$fileSystem = FileSystem::factory();

$filename = $_GET['file'] ?? '';

// Validate the filename
if (!$filename || !preg_match('/^[a-zA-Z0-9_\-.]+\.(pdf)$/', $filename)) {
    // Invalid file name or file type
    header("HTTP/1.1 400 Bad Request");
    exit('Invalid file request:' . $filename);
}

$paper = $fs->getPapersJsonFor($filename);
if ($paper === null) {
    header("HTTP/1.1 404 Not Found");
    exit('File metadata not found');
}

$filePath = $docsDir . '/' . $filename;

// Check if the file exists
if (!file_exists($filePath) || !is_readable($filePath)) {
    header("HTTP/1.1 404 Not Found");
    exit('File not found');
}

$realFilename = $paper['filename'];

// Set headers for file download
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($realFilename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

// Clear output buffer before reading the file
ob_clean();
flush();

// Read the file and output its content
readfile($filePath);

exit;