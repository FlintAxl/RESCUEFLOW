<?php
session_start();
include('../includes/check_admin.php');

include('../includes/config.php');
include('../includes/restrict_admin.php');
// Fetch members data
$sql = "SELECT members.member_id, members.first_name, members.last_name, members.email, members.phone, members.image, 
               ranks.rank_name, roles.role_name 
        FROM members
        LEFT JOIN ranks ON members.rank_id = ranks.rank_id
        LEFT JOIN roles ON members.role_id = roles.role_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Personnel Management</h1>
        <a href="create.php" class="btn btn-primary mb-3">Add New Personnel</a>
        <table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Rank</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['member_id']; ?></td>
                <td>
    <?php if (!empty($row['image']) && file_exists("../personel/images/" . $row['image'])): ?>
        <img src="../personel/images/<?= htmlspecialchars($row['image']); ?>" width="50" height="50" class="rounded-circle">
    <?php else: ?>
        <img src="../personel/images/default.jpg" width="50" height="50" class="rounded-circle">
    <?php endif; ?>
</td>


                <td><?= $row['first_name']; ?></td>
                <td><?= $row['last_name']; ?></td>
                <td><?= $row['email']; ?></td>
                <td><?= $row['phone']; ?></td>
                <td><?= $row['rank_name'] ?? 'N/A'; ?></td>
                <td><?= $row['role_name'] ?? 'N/A'; ?></td>
                <td>
                    <a href="edit.php?member_id=<?= $row['member_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?member_id=<?= $row['member_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
                    <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
?>