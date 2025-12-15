<header>
    <?= $title ?>
</header>

<form method="post" action="?action=<?= $action ?>">

    <?php if($action === 'update-group'): ?>
        <input type="hidden" id="id" name="id" value="<?= $_POST['id'] ?? $group['id'] ?>">
    <?php endif; ?>

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $_POST['name'] ?? $group['name'] ?? '' ?>" maxlength="255" required>
    </div>

    <div>
        <button type="submit">Zapisz</button>
    </div>
</form>
