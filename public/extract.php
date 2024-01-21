<?php
// extract.php
require_once '../vendor/autoload.php';

use Librarian\FileSystem;
use Librarian\Configuration;

$config = Configuration::getInstance();
$fileSystem = FileSystem::factory();

$filename = $_GET['file'] ?? '';

if (!$filename) {
    echo "No file specified.";
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
$title = "Extracted Text";
$subtitle = $paper['filename'];

// Extract all text from the PDF
$text = $fileSystem->getPdfText($filename, -1, -1);

include '../views/header.tpl.php';
if ($text !== null) {
?>
<body>
    <h3 class="m2"><?= htmlspecialchars($subtitle) ?></h3>
    <div class="content rounder forebox">
        <?php
        // Convert newlines to <br> tags for HTML display
        echo nl2br(htmlspecialchars($text));
        ?>
    </div>
</body>
<?php
} else {
    echo "Failed to extract text from the PDF.";
}

include '../views/footer.tpl.php';
