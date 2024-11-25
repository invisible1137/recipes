<?php
// файл users/list.php

$users = getAll('users');
?>

<h2>User List</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Age</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['age']) ?></td>
                <td><?= htmlspecialchars($user['created_at']) ?></td>
                <td><?= isset($user['updated_at']) ? htmlspecialchars($user['updated_at']) : 'N/A' ?></td>
                <td>
                    <a href="?app=users&view=update&id=<?= htmlspecialchars($user['id']) ?>">Edit</a>
                    <a href="?app=users&view=delete&id=<?= htmlspecialchars($user['id']) ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
