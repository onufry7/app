<header>
    <?= $title ?>
</header>

<form method="post" data-action="<?= $action ?>" id="userForm">

    <?php if($action === 'update-user'): ?>
        <input type="hidden" id="id" name="id" value="<?= $_POST['id'] ?? $user['id'] ?>">
    <?php endif; ?>

    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $_POST['name'] ?? $user['name'] ?? '' ?>" maxlength="255" required>
    </div>

    <div>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" maxlength="255"  <?php if($action === 'store-user'): ?> required <?php endif; ?>>
    </div>

    <div>
        <label for="first_name">First name:</label>
        <input type="text" id="first_name" name="first_name" value="<?= $_POST['first_name'] ?? $user['first_name'] ?? '' ?>" maxlength="255">
    </div>

    <div>
        <label for="last_name">Last name:</label>
        <input type="text" id="last_name" name="last_name" value="<?= $_POST['last_name'] ?? $user['last_name'] ?? '' ?>" maxlength="255">
    </div>

    <div>
        <label for="birth_date">Date of birth:</label>
        <input type="date" id="birth_date" name="birth_date" value="<?= $_POST['birth_date'] ?? $user['birth_date'] ?? '' ?>">
    </div>

    <div>
        <button type="submit"><?= isset($user) ? 'Update' : 'Add' ?></button>
    </div>
</form>
