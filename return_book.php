<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$msg = "";

// Handle return action
if (isset($_POST['issued_id'])) {
    $issued_id = intval($_POST['issued_id']);

    // Get the issued book record
    $stmt = $conn->prepare("SELECT book_id FROM issued_books WHERE id = ? AND return_date IS NULL");
    $stmt->bind_param("i", $issued_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $book_id = $row['book_id'];

        // Update return_date
        $update = $conn->prepare("UPDATE issued_books SET return_date = CURDATE() WHERE id = ?");
        $update->bind_param("i", $issued_id);
        $update->execute();
        $update->close();

        // Update book status to Available
        $updateBook = $conn->prepare("UPDATE books SET status = 'Available' WHERE id = ?");
        $updateBook->bind_param("i", $book_id);
        $updateBook->execute();
        $updateBook->close();

        $msg = "✅ Book returned successfully!";
    } else {
        $msg = "❌ Invalid return request.";
    }
    $stmt->close();
}

// Fetch issued books not yet returned
$sql = "SELECT ib.id, b.title, ib.issued_to, ib.issue_date
        FROM issued_books ib
        JOIN books b ON ib.book_id = b.id
        WHERE ib.return_date IS NULL
        ORDER BY ib.issue_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 40px; }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto 20px auto;
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
            margin-bottom: 20px;
        }
        form { margin: 0; }
        input[type=submit] {
            background: #990000;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background: #cc0000;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: green;
        }
        a {
            display: block;
            text-align: center;
            color: #990000;
            font-weight: bold;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<h2>Return Issued Book</h2>

<?php if ($msg) echo "<div class='message'>$msg</div>"; ?>

<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Book Title</th>
            <th>Issued To</th>
            <th>Issue Date</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['issued_to']); ?></td>
                <td><?php echo htmlspecialchars($row['issue_date']); ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Confirm return of this book?');">
                        <input type="hidden" name="issued_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Return Book">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p style="text-align:center;">No books currently issued.</p>
<?php endif; ?>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
