<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "listener") {
    session_destroy();
    header("location:../index.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_SESSION['USERNAME']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Listener Dashboard - PINOTIFY</title>
</head>
<body style="font-family:Arial;background:#f5eee8;margin:0;padding:0;">

<div style="width:1100px;margin:30px auto;background:white;padding:25px;border-radius:4px;box-shadow:0 2px 5px rgba(0,0,0,0.05);">
    <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #f5eee8; padding-bottom:15px;">
        <div>
            <h1 style="margin:0; color:#333;">PINOTIFY</h1>
            <p style="margin:5px 0 0 0; color:#777;">Welcome back, <b><?php echo htmlspecialchars($username); ?></b> (Listener Mode)</p>
        </div>
        <p><a href="logout.php" style="color:#cc0000; text-decoration:none; font-weight:bold;">Logout</a></p>
    </div>

    <h3 style="margin-top:25px; color:#444;">🎵 Available Tracks in Baguio City</h3>
    <table border="1" cellpadding="10" style="width:100%;border-collapse:collapse;border:1px solid #ddd; margin-top:15px;">
        <tr style="background:#f9f9f9; text-align:left;">
            <th>Cover</th>
            <th>Song Details</th>
            <th>Release Date</th>
            <th>Duration</th>
            <th style="text-align:center;">Listen</th>
        </tr>
        <?php
        // Query tracks library ordered from freshest releases down to oldest
        $result = mysqli_query($conn, "SELECT * FROM song ORDER BY song_id DESC");
        
        if (!$result || mysqli_num_rows($result) === 0) {
            echo "<tr><td colspan='5' style='text-align:center;color:red;padding:30px;'>No tracks are currently streaming in the database area.</td></tr>";
        } else {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td style="width:60px; text-align:center;">
                        <?php if (!empty($row['cover_image_url'])): ?>
                            <img src="../<?php echo htmlspecialchars($row['cover_image_url']); ?>" alt="Cover" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                        <?php else: ?>
                            <div style="width:50px;height:50px;background:#eee;line-height:50px;font-size:10px;color:#999;border-radius:4px;">No Disc</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span style="font-size:16px; font-weight:bold; color:#222;"><?php echo htmlspecialchars($row['title']); ?></span><br>
                        <span style="font-size:12px; color:#666;">Duration Marker: <?php echo htmlspecialchars($row['duration']); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($row['release_date']); ?></td>
                    <td><span style="background:#e1f5fe; color:#0288d1; padding:3px 8px; border-radius:12px; font-size:12px; font-weight:bold;">🔥 <?php echo number_format((int)$row['play_count']); ?> streams</span></td>
                    <td style="text-align:center; width:320px;">
                        <?php if (!empty($row['audio_url'])): ?>
                            <!-- Action form to capture streaming telemetry clicks inside log_play.php -->
                            <form method="post" action="log_play.php" target="_blank" style="margin:0; display:inline;">
                                <input type="hidden" name="song_id" value="<?php echo $row['song_id']; ?>">
                                <input type="hidden" name="audio_url" value="<?php echo $row['audio_url']; ?>">
                                <audio controls style="height:320px; vertical-align:middle; height:32px;" onplay="this.form.submit();">
                                    <source src="../<?php echo htmlspecialchars($row['audio_url']); ?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </form>
                        <?php else: ?>
                            <span style="color:#999; font-style:italic;">No Media Link</span>
                        <?php endif; ?>
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
