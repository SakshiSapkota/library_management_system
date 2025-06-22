<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Amrit Science Campus Library</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff0f0;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #990000;
        }
        .nav-links {
            margin-top: 40px;
        }
        a {
            display: inline-block;
            margin: 15px 25px;
            padding: 15px 30px;
            background: #990000;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 8px;
            transition: background 0.3s;
        }
        a:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>

<div class="nav-links">
    <a href="add_book.php">➕ Add Book</a>
    <a href="view_books.php">📚 View Books</a>
    <a href="issue_book.php">📖 Issue Book</a>
    <a href="return_book.php">↩️ Return Book</a>
    <a href="view_issued_books.php">📋 Issued Books</a>
    <a href="view_users.php">👥 View Users</a>
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>
