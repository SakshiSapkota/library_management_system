<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

// Fetch all books
$result = $conn->query("SELECT * FROM books ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Books</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        table {
            border-collapse: collapse;
            width: 90%;
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

<h2>All Books</h2>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>Status</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author']); ?></td>
                <td><?php echo htmlspecialchars($row['year']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">No books found.</p>
<?php endif; ?>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
