<p><?php echo htmlspecialchars($message); ?></p>

<?php if (isset($uploadStatus)): ?>
<h2>Details:</h2>
<ul>
<?php foreach ($uploadStatus as $key => $value): 
    // if we have a list, join it
    if (is_array($value)) {
        $value = implode(', ', $value);
    }
    ?>
    <li><?php echo htmlspecialchars(ucwords($key)) . ': ' . htmlspecialchars($value); ?></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>