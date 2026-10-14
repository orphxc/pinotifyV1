<?php
session_start();
if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>User Home - CafeEase</title></head>
<body style="font-family:Arial;background:#f5eee8;">
<div style="width:800px;margin:30px auto;background:white;padding:25px;">
<h1>Welcome <?php echo htmlspecialchars($_SESSION['USERNAME']); ?>!</h1>
<hr>
<h1>HOMEPAGE</h1>
<h3><a href="songlist.php">Songs Lists</a></h3>
<h3><a href="add_song.php">+ ADD SONG</a></h3>
<h3><a href="view_songs.php">My Songs</a></h3>
<h3><a href="logout.php">Logout</a></h3>
</div>
</body>
</html>
