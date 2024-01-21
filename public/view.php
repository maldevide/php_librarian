<?php
// view.php
require_once '../vendor/autoload.php';
require_once '../src/FileSystem.php';
require_once '../src/Configuration.php';

use Librarian\FileSystem;
use Librarian\Configuration;
use Librarian\ConfigurationKey;

$config = Configuration::getInstance();
$fileSystem = FileSystem::factory();

$filename = $_GET['file'] ?? '';

if (!$filename) {
    echo "No file specified.";
    exit;
}

$title = "Document Details";
$to_home = true;
$paper = $fileSystem->getPapersJsonFor($filename);


if ($paper === null) {
    $message = "Metadata not found.";
    include '../views/header.tpl.php';
    include '../views/error.tpl.php';
    include '../views/footer.tpl.php';
    exit;
}

$document = $paper;

include '../views/header.tpl.php';
include '../views/view_document.tpl.php';
include '../views/footer.tpl.php';

?>