<?php
// batchupload.php
require_once '../vendor/autoload.php';

use Librarian\FileSystem;
use Librarian\DocumentUpload;

$fileSystem = FileSystem::factory();
$documentUpload = new DocumentUpload($fileSystem);
$uploadSummaries = [];
$title = "Batch File Upload";
$to_home = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['files'])) {
        $uploadedFiles = $_FILES['files'];

        for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
            // Collect file data
            $fileData = [
                'name' => $uploadedFiles['name'][$i],
                'type' => $uploadedFiles['type'][$i],
                'tmp_name' => $uploadedFiles['tmp_name'][$i],
                'error' => $uploadedFiles['error'][$i],
                'size' => $uploadedFiles['size'][$i]
            ];

            // Process each file
            $result = $documentUpload->uploadFile($fileData);
            if (isset($result['error'])) {
                $uploadSummaries[] = "Failed to upload {$fileData['name']}: {$result['error']}";
            } else {
                $uploadSummaries[] = "Successfully uploaded {$fileData['name']}: {$result['success']}";
            }
        }
    } else {
        $maxSize = DocumentUpload::getMaximumUploadSize();
        $uploadSummaries[] = "No files uploaded. Maximum upload size is {$maxSize}.";
    }
}

include '../views/header.tpl.php';
include '../views/batch_upload.tpl.php';
include '../views/footer.tpl.php';