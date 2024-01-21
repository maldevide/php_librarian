    <h3><?= htmlspecialchars($filename) ?></h3>
    <p>Are you sure you want to delete the document <strong><?= htmlspecialchars($filename) ?></strong>?</p>
    <p>This action cannot be undone.</p>
    <i>Enter the passkey to confirm deletion.</i>
    <hr>
    <form action="delete.php" method="post">
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <input type="hidden" name="file" value="<?= htmlspecialchars($filename) ?>">
        <label for="passkey">Enter passkey to delete:</label>
        <input type="password" name="passkey" id="passkey">
        <button type="submit">Delete</button>
    </form>