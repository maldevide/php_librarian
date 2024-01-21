<div class="document-info">
    <div class="flexbar">
        <span><strong>
                <?= $document['filename'] ?>
            </strong></span>
        <div>
            <a href="download.php?file=<?= $filename ?>" target="_blank">Download</a>
            <a href="extract.php?file=<?= $filename ?>">View Text</a>
            <a href="delete.php?file=<?= $filename ?>">Delete</a>
        </div>
    </div>
    <div class="document-grid">
        <div class="document-info">
            <?php foreach ($document as $key => $value): ?>
                <div class="<?= in_array($key, ['clip', 'filename', 'notes']) ? 'full' : '' ?>">
                    <strong><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?>:</strong>
                    <?php
                    if (is_array($value)) {
                        echo htmlspecialchars(implode(', ', $value));
                    } else {
                        echo htmlspecialchars($value);
                        if ($key === 'size') echo ' bytes'; // Append 'bytes' to size
                    }
                    ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
