<p>Group name: <?= htmlspecialchars($group['name']) ?> </p>
<p>Users:</p>

<div class="listItems">
    <?php foreach($group['users'] as $user): ?>
        <span>
            <?= htmlspecialchars($user['name'] ?? '') ?>
            <a href="index.php?action=delete-user-group&id=<?= htmlspecialchars($user['user_group_id'] ?? '')?>&group_id=<?= htmlspecialchars($group['id'] ?? '') ?>" title="remove">X</a>, 
        </span>
    <?php endforeach ?>
</div>

<div class="buttonsFooter">
    <a href="index.php?action=edit-group&id=<?= htmlspecialchars($group['id']) ?>">Edit group</a>
    <a href="index.php?action=delete-group&id=<?= htmlspecialchars($group['id']) ?>">Delete group</a>
    <a href="index.php?action=add-user-group&id=<?= htmlspecialchars($group['id']) ?>">Add user to group</a>
</div>
