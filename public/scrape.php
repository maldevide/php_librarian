<?php
// scrape.php
require_once '../vendor/autoload.php';

use Librarian\FileSystem;
use Librarian\DocumentUpload;

$title = "Scrape PDF";
$message = "";
$to_home = true;

function downloadFile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['success' => false, 'error' => $error];
    }

    return ['success' => true, 'data' => $data];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['uri'])) {
    $url = filter_var($_POST['uri'], FILTER_SANITIZE_URL);

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        $message = "Invalid URL.";
    } else {
        $download = downloadFile($url);

        if (!$download['success']) {
            $message = "Error downloading the file: " . $download['error'];
        } else {
            $tempdir = __DIR__ . '/../local/tmp';
            $tempFile = tempnam($tempdir, 'pdf');
            file_put_contents($tempFile, $download['data']);

            $fileSystem = FileSystem::factory();
            $documentUpload = new DocumentUpload($fileSystem);

            $file = [
                'name' => basename(parse_url($url, PHP_URL_PATH)),
                'type' => 'application/pdf',
                'tmp_name' => $tempFile,
                'error' => 0,
                'size' => filesize($tempFile)
            ];

            $result = $documentUpload->uploadFile($file, 'move');
            if (isset($result['error'])) {
                $error = $result['error'];
                $message = "Failed to upload file: {$error}";
                unlink($tempFile);
            } else {
                $uploadStatus = $result['status'];
                $message = "File uploaded successfully.";
            }

        }
    }
} else {
    $message = "Enter the URL of the PDF to scrape.";
}

include '../views/header.tpl.php';

if (empty($_POST['uri']) || !empty($message)) {
    ?>
    <form action="scrape.php" method="post">
        <label for="uri">PDF URL:</label>
        <input type="text" id="uri" name="uri" required>
        <input type="submit" value="Scrape PDF">
    </form>
    <?php
}

include '../views/upload_result.tpl.php';
include '../views/footer.tpl.php';
