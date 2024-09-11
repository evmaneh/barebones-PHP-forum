<?php
session_start();
header('Location: home.php');
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
</head>
<body>
    <a href="home.php">If you aren't redirected, click here.</a>
    <p>You are logged in.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
