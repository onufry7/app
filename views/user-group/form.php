<header>
    <?= $title ?>
</header>

<form method="post" data-action="store-user-group" id="userGroupForm">

    <input type="hidden" id="group_id" name="group_id" value="<?= $_GET['id'] ?? $group['id'] ?>">

    <div>
        <label for="user_id">User:</label>
        <select name="user_id" id="user_id">
            <?php foreach ($users as $user): ?>
                <option value="<?= (int)$user['id'] ?>">
                    <?= htmlspecialchars($user['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <button type="submit">Save</button>
    </div>
</form>
