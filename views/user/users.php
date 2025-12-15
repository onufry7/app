<header>
    User List
</header>
<div class="buttonsHeader">
    <a href="index.php?action=add-user">Add user</a>
</div>
<table>
    <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= htmlspecialchars($user['name'] ?? ''); ?></td>
                <td><?= htmlspecialchars($user['first_name'] ?? ''); ?></td>
                <td><?= htmlspecialchars($user['last_name'] ?? ''); ?></td>
                <td><?= htmlspecialchars($user['birth_date'] ?? ''); ?></td>
                <td>
                    <a href="index.php?action=show-user&id=<?= htmlspecialchars($user['id']); ?>">Show</a>
                </td>
            </tr>
        
        <p></p> 
    <?php endforeach; ?>
</table>