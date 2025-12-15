<p>Name: <?= htmlspecialchars($user['name'] ?? '') ?> </p>
<p>First name: <?= htmlspecialchars($user['first_name'] ?? '') ?> </p>
<p>Last name: <?= htmlspecialchars($user['last_name'] ?? '') ?> </p>
<p>Date of birth: <?= htmlspecialchars($user['birth_date'] ?? '') ?> </p>

<p>Groups: </p>
<div class="listItems">
    <?php foreach($user['groups'] as $group): ?>
        <span>
            <?= htmlspecialchars($group['name'] ?? '') ?>, 
        </span>
    <?php endforeach ?>
</div>

<div class="buttonsFooter">
    <a href="index.php?action=edit-user&id=<?= htmlspecialchars($user['id']) ?>">Edit user</a>
    <a href="index.php?action=delete-user&id=<?= htmlspecialchars($user['id']) ?>">Delete user</a>
</div>
