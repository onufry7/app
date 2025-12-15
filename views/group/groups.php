<header>
    Group List
</header>
<div class="buttonsHeader">
    <a href="index.php?action=add-group">Add group</a>
</div>
<table>
    <?php foreach ($groups as $group) : ?>
            <tr>
                <td><?= htmlspecialchars($group['name']); ?></td>
                <td>
                    <a href="index.php?action=show-group&id=<?= htmlspecialchars($group['id']); ?>">Show</a>
                </td>
            </tr>
        
        <p></p> 
    <?php endforeach; ?>
</table>