<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

include 'db.php';

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST["title"]);
    $author = trim($_POST["author"]);
    $year = intval($_POST["year"]);

    $stmt = $conn->prepare("INSERT INTO books (title, author, year) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $author, $year);

    if ($stmt->execute()) {
        $msg = "✅ Book added successfully!";
    } else {
        $msg = "❌ Failed to add book.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 50px; text-align: center; }
        form { background: white; display: inline-block; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        input { margin: 10px; padding: 10px; width: 90%; }
        input[type="submit"] { background: #990000; color: white; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #cc0000; }
        a { display: inline-block; margin-top: 20px; text-decoration: none; color: #990000; }
    </style>
</head>
<body>

<h2>Add a New Book</h2>
<?php if ($msg) echo "<p>$msg</p>"; ?>
<form method="POST">
    <input type="text" name="title" placeholder="Book Title" required><br>
    <input type="text" name="author" placeholder="Author"><br>
    <input type="number" name="year" placeholder="Published Year"><br>
    <input type="submit" value="Add Book">
</form>

<a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
