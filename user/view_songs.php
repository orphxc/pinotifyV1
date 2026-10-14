<?php
session_start();
include '../dbconn.php';

// Route back to landing if criteria aren't met
if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Added Songs</title>
</head>
<body style="font-family:Arial;background:#f5eee8;">

<div style="width:1150px;margin:30px auto;background:white;padding:25px;">
    <h1>Tracks Library</h1>
    <p>
        <a href="index.php">Home</a> | 
        <a href="add_song.php">Add another song</a> |
        <a href="logout.php">Logout</a>
    </p>

    <table border="1" cellpadding="10" style="width:100%;border-collapse:collapse;">
        <tr style="background:#f2f2f2;">
            <th>Song ID</th>
            <th>Title</th>
            <th>Duration / Date Logged</th>
            <th>Release Date</th>
            <th>Cover Image</th>
            <th>Audio Link</th>
            <th>Plays</th>
            <th>Action</th>
        </tr>
        <?php
        // Fetch all elements ordered from newest entries down to oldest
        $result = mysqli_query($conn, "SELECT * FROM song ORDER BY song_id DESC");
        
        if (!$result || mysqli_num_rows($result) === 0) {
            echo "<tr><td colspan='8' style='text-align:center;color:red;padding:20px;'>No tracks found in the database. <a href='add_song.php'>Click here to add one</a>.</td></tr>";
        } else {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td>#<?php echo htmlspecialchars($row['song_id']); ?></td>
                    <td><b><?php echo htmlspecialchars($row['title']); ?></b></td>
                    <td><?php echo htmlspecialchars($row['duration']); ?></td>
                    <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                    <td>
                        <?php if (!empty($row['cover_image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($row['cover_image_url']); ?>" alt="Cover" style="width:50px;height:50px;object-fit:cover;">
                        <?php else: ?>
                            <span style="color:#888; font-style:italic;">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['audio_url'])): ?>
                            <a href="<?php echo htmlspecialchars($row['audio_url']); ?>" target="_blank" style="color:#0066cc; text-decoration:none; font-weight:bold;">Listen Track</a>
                        <?php else: ?>
                            <span style="color:#888; font-style:italic;">Unavailable</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo number_format((int)$row['play_count']); ?></td>
                    <td>
                        <form method="post" action="index.php">
                            <input type="hidden" name="song_id" value="<?php echo $row['song_id']; ?>">
                            <button type="submit" name="select_song" style="padding: 5px 10px; cursor: pointer; background:#fff; border:1px solid #777;">Select Song</button>
                        </form>
                    </td>
                </tr>
                <?php 
            }
        } 
        ?>
    </table>
</div>

</body>
</html>
