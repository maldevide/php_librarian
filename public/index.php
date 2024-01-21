<?php
// index.php
require_once '../vendor/autoload.php';

use Librarian\FileSystem;

$title = "Document Library";
try {
    $fileSystem = FileSystem::factory();
    $documents = $fileSystem->getPapersJson();
} catch (Exception $e) {
    $documents = [];
}

include '../views/header.tpl.php';
include '../views/dashboard.tpl.php';
include '../views/footer.tpl.php';

?>
