<form action="batchupload.php" method="post" enctype="multipart/form-data">
<input type="file" name="files[]" multiple>
<input type="submit" value="Upload Files">
</form>

<?php if (!empty($uploadSummaries)): ?>
<h2>Upload Summary</h2>
<ul>
    <?php foreach ($uploadSummaries as $summary): ?>
        <li><?= htmlspecialchars($summary) ?></li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>