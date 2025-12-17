<p>Group name: <?= htmlspecialchars($group['name']) ?> </p>
<p>Users:</p>

<div class="listItems">
    <?php foreach($group['users'] as $user): ?>
        <form method="post" data-action="delete-user-group" class="deleteForm">
            <span><?= htmlspecialchars($user['name'] ?? '') ?></span> 
            <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
            <input type="hidden" name="group_id" value="<?= (int) $group['id'] ?>">
            <button type="submit" class="link" title="remove">X</button>
        </form>
    <?php endforeach ?>
</div>

<div class="buttonsFooter">
    <a href="index.php?action=edit-group&id=<?= htmlspecialchars($group['id']) ?>">Edit group</a>
    <form method="post" data-action="delete-group" class="deleteForm">
        <input type="hidden" name="id" value="<?= (int) $group['id'] ?>">
        <button type="submit" class="link" title="remove group">Delete group</button>
    </form>
    <a href="index.php?action=add-user-group&id=<?= htmlspecialchars($group['id']) ?>">Add user to group</a>
</div>
