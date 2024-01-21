<?php
// delete.php
require_once '../vendor/autoload.php';

use Librarian\FileSystem;

$fileSystem = FileSystem::factory();
$to_home = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passkey = $_POST['passkey'] ?? '';
    $filename = $_POST['file'] ?? '';
    $title = "Delete Document";
    $error = '';

    if (!$filename) {
        $message = "No file specified.";
        include '../views/header.tpl.php';
        include '../views/error.tpl.php';
        include '../views/footer.tpl.php';
        exit;
    }

    if ($passkey === 'testtest') {
        // Delete the paper
        include '../views/header.tpl.php';
        if ($fileSystem->deletePaper($filename)) {
            $message = "File '{$filename}' has been deleted.";
            include '../views/success.tpl.php';
        } else {
            $message = "Failed to delete file '{$filename}'.";
            include '../views/error.tpl.php';
        }
        include '../views/footer.tpl.php';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filename = $_GET['file'] ?? '';
    $title = "Delete Document";

    if (!$filename) {
        $message = "No file specified.";
        include '../views/header.tpl.php';
        include '../views/error.tpl.php';
        include '../views/footer.tpl.php';
        exit;
    }

    $paper = $fileSystem->getPapersJsonFor($filename);

    if ($paper === null) {
        $message = "File not found.";
        include '../views/header.tpl.php';
        include '../views/error.tpl.php';
        include '../views/footer.tpl.php';
        exit;
    }

} else {
    $message = "Invalid request method.";
    include '../views/header.tpl.php';
    include '../views/error.tpl.php';
    include '../views/footer.tpl.php';
    exit;
}

include '../views/header.tpl.php';
include '../views/delete_form.tpl.php';
include '../views/footer.tpl.php';