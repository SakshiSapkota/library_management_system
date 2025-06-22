<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Get all users except maybe admin (optional)
$result = $conn->query("SELECT id, username FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #990000;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f7f7f7;
        }
        h2 {
            text-align: center;
            color: #990000;
        }
        a {
            display: inline-block;
            margin: 20px auto;
            text-decoration: none;
            color: #990000;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Registered Users</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">No users found.</p>
<?php endif; ?>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
