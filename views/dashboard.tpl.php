<div class="document-info">
<!-- File Upload Button -->
<div class="flexbar">
    <button id="uploadBtn" onclick="triggerFileInput()">Upload Document</button>
    <form id="fileUploadForm" action="upload.php" method="post" enctype="multipart/form-data" style="display:none;">
        <input type="file" name="pdfFile" id="pdfFile" onchange="fileSelected()">
        <input type="submit" value="Upload PDF" name="submit">
    </form>
    <button id="batchUploadBtn" onclick="window.location.href='batchupload.php'">Batch Upload</button>
</div>

<!-- File Upload Modal -->
<ul id="documentList">
    <!-- List documents here -->
    <?php foreach ($documents as $key => $document): 
        if ($document === null) {
            ?>
            <li>
                <span class="caret" key="<?=$key?>" onclick="toggleMetadata(this)"><?= htmlspecialchars($key) ?></span>
                <span>File not found.</span>
            </li>
            <?php
        }
        $filename = htmlspecialchars($document['filename']);
        $title = htmlspecialchars($document['title'] ?? '');  
        ?>
        <li>
            <span class="caret" key="<?=$key?>" onclick="toggleMetadata(this)"></span>
            <span><a href="view.php?file=<?= urlencode($key) ?>"><?=$filename?></a><br/><?=$title?></span>
            <div class="nested document-grid" key="<?=$key?>" style="display: none;">
                <div class="document-info">
                <div><strong>Filetype:</strong> <?= htmlspecialchars($document['filetype']) ?></div>
                <div><strong>Size:</strong> <?= htmlspecialchars($document['size']) ?> bytes</div>
                <div><strong>Uploaded On:</strong> <?= htmlspecialchars($document['uploaded_on']) ?></div>
                <div><strong>Status:</strong> <?= htmlspecialchars($document['status']) ?></div>
                <div><strong>Clip:</strong> <?= htmlspecialchars($document['clip']) ?></div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<script>
function triggerFileInput() {
    document.getElementById('pdfFile').click();
}

function fileSelected() {
    var fileInput = document.getElementById('pdfFile');
    if (fileInput.value) {
        document.getElementById('fileUploadForm').style.display = 'block';
    }
}

    function toggleMetadata(caretElement) {
            // Close all open nested content
var allNested = document.getElementsByClassName("nested");
for (var i = 0; i < allNested.length; i++) {
    allNested[i].style.display = 'none';
}

var closedMe = false;

// Close all carets
var allCarets = document.getElementsByClassName("caret");
for (var i = 0; i < allCarets.length; i++) {
    // if our key matches the key of the caret we clicked, then we're closing it
    // if it is open
    if (allCarets[i].getAttribute("key") === caretElement.getAttribute("key")) {
        if (allCarets[i].classList.contains("caret-down")) {
            closedMe = true;
        }
    }
    allCarets[i].classList.remove("caret-down");
}
// Open the clicked nested content
if (!closedMe) {
    var nestedContent = caretElement.nextElementSibling.nextElementSibling;
    if (nestedContent.style.display !== 'block') {
        nestedContent.style.display = 'block';
        caretElement.classList.add("caret-down");
    } else {
        nestedContent.style.display = 'none';
        caretElement.classList.remove("caret-down");
    }
}
}
function old(caret) {
        // Get all nested elements
        var allNested = document.querySelectorAll('.nested');
        
        // Close all nested elements
        allNested.forEach(function(nested) {
            nested.style.display = 'none';
            nested.previousElementSibling.previousElementSibling.classList.remove('caret-down');
        });

        // Get the nested content for the clicked caret
        var nestedContent = caretElement.nextElementSibling.nextElementSibling;
        
        // Toggle the display of the target nested content
        if (nestedContent.style.display === 'none' || nestedContent.style.display === '') {
            nestedContent.style.display = 'block';
            caretElement.classList.add('caret-down');
        } else {
            nestedContent.style.display = 'none';
            caretElement.classList.remove('caret-down');
        }
    }
</script>
</div>