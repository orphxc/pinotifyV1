<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Song Playlist</title>
</head>
<body style="font-family:Arial;background:#f5eee8;">

<div style="width:1150px;margin:30px auto;background:white;padding:25px;">
    <h1>Available Songs</h1>
    <p>
        <a href="index.php">Back to Homepage</a> | 
        <a href="add_song.php">Add a Song</a> | 
        <a href="view_songs.php">My Songs</a> | 
        <a href="logout.php">Logout</a>
    </p>
    
    <table border="1" cellpadding="10" style="width:100%;border-collapse:collapse;">
        <tr>
            <th>Song ID</th>
            <th>Title</th>
            <th>Duration/Timestamp</th>
            <th>Release Date</th>
            <th>Cover Image</th>
            <th>Audio Link</th>
            <th>Plays</th>
            <th>Action</th>
        </tr>
        <?php
        // Querying the correct 'song' table from your database schema
        $result = mysqli_query($conn, "SELECT * FROM song ORDER BY song_id DESC");
        
        if (!$result || mysqli_num_rows($result) === 0) {
            echo "<tr><td colspan='8' style='text-align:center;color:red;padding:20px;'>No available songs found in the database.</td></tr>";
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
                            <em>No Image</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['audio_url'])): ?>
                            <a href="<?php echo htmlspecialchars($row['audio_url']); ?>" target="_blank">Listen</a>
                        <?php else: ?>
                            <em>Unavailable</em>
                        <?php endif; ?>
                    </td>
                    <td><?php echo number_format((int)$row['play_count']); ?></td>
                    <td>
                        <!-- Form targeting index.php to process selection actions -->
                        <form method="post" action="index.php">
                            <input type="hidden" name="song_id" value="<?php echo $row['song_id']; ?>">
                            <button type="submit" name="select_song" style="padding: 5px 10px; cursor: pointer;">Select Song</button>
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
