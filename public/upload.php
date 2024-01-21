<?php
// upload.php
require_once '../vendor/autoload.php';

use Librarian\FileSystem;
use Librarian\Configuration;
use Librarian\ConfigurationKey;
use Librarian\DocumentUpload;

$title = "Upload Status";
$config = Configuration::getInstance();
$docsDir = $config->get(ConfigurationKey::DocsPath);
$to_home = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdfFile'])) {
    $file = $_FILES['pdfFile'];

    $fileSystem = FileSystem::factory();
    $documentUpload = new DocumentUpload($fileSystem);

    $result = $documentUpload->uploadFile($file);
    if (isset($result['error'])) {
        $error = $result['error'];
        $message = "Failed to upload file: {$error}";
    } else {
        $uploadStatus = $result['status'];
        $message = "File uploaded successfully.";
    }
} else {
    $message = "No file uploaded or invalid access.";
}

include '../views/header.tpl.php';
include '../views/upload_result.tpl.php';
include '../views/footer.tpl.php';

?>
