<?php
session_start();
include('../includes/check_admin.php');
include('../includes/config.php');

// Fetch members data
$sql = "SELECT members.member_id, members.first_name, members.last_name, members.email, 
               members.phone, members.image, ranks.rank_name, roles.role_name 
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="text-center">Personnel Management</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Rank</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['member_id']); ?></td>
                                <td>
                                    <?php 
                                    $imagePath = "../personel/images/" . $row['image'];
                                    $defaultImage = "../personel/images/default.jpg";
                                    ?>
                                    <img src="<?= file_exists($imagePath) ? $imagePath : $defaultImage; ?>" 
                                         width="50" height="50" class="rounded-circle">
                                </td>
                                <td><?= htmlspecialchars($row['first_name']); ?></td>
                                <td><?= htmlspecialchars($row['last_name']); ?></td>
                                <td><?= htmlspecialchars($row['email']); ?></td>
                                <td><?= htmlspecialchars($row['phone']); ?></td>
                                <td><?= htmlspecialchars($row['rank_name'] ?? 'N/A'); ?></td>
                                <td><?= htmlspecialchars($row['role_name'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <?php if ($result->num_rows == 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No personnel records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
