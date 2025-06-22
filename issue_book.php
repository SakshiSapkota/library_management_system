<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$msg = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = intval($_POST['book_id']);
    $issued_to = trim($_POST['issued_to']);
    $issue_date = date('Y-m-d');

    // Insert issue record
    $stmt = $conn->prepare("INSERT INTO issued_books (book_id, issued_to, issue_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $book_id, $issued_to, $issue_date);

    if ($stmt->execute()) {
        // Update book status
        $update = $conn->prepare("UPDATE books SET status = 'Issued' WHERE id = ?");
        $update->bind_param("i", $book_id);
        $update->execute();
        $update->close();

        $msg = "✅ Book issued successfully!";
    } else {
        $msg = "❌ Failed to issue book.";
    }
    $stmt->close();
}

// Get all available books
$books = $conn->query("SELECT id, title FROM books WHERE status = 'Available' ORDER BY title");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Issue Book</title>
    <style>
        body { font-family: Arial; background: #f9f9f9; padding: 50px; text-align: center; }
        form { background: white; padding: 30px; border-radius: 8px; display: inline-block; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        select, input[type=text] {
            width: 300px; padding: 10px; margin: 10px 0;
            border: 1px solid #ccc; border-radius: 5px;
        }
        input[type=submit] {
            background: #990000; color: white; padding: 10px 20px; border: none; border-radius: 5px;
            cursor: pointer; font-weight: bold;
        }
        input[type=submit]:hover { background: #cc0000; }
        a { display: block; margin-top: 20px; color: #990000; text-decoration: none; }
    </style>
</head>
<body>

<h2>Issue a Book</h2>

<?php if ($msg) echo "<p>$msg</p>"; ?>

<?php if ($books->num_rows > 0): ?>
<form method="POST">
    <select name="book_id" required>
        <option value="">Select a book</option>
        <?php while($row = $books->fetch_assoc()): ?>
            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></option>
        <?php endwhile; ?>
    </select><br>
    <input type="text" name="issued_to" placeholder="Issued to (Name)" required><br>
    <input type="submit" value="Issue Book">
</form>
<?php else: ?>
    <p>No available books to issue.</p>
<?php endif; ?>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
