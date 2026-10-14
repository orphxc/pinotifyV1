<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}

$message = "";

// HANDLE ADD SONG FORM SUBMISSION
if (isset($_POST['add_song'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $release_date = mysqli_real_escape_string($conn, $_POST['release_date']);
    $cover_image_url = mysqli_real_escape_string($conn, $_POST['cover_image_url']);
    $audio_url = mysqli_real_escape_string($conn, $_POST['audio_url']);
    $artist_id = (int)$_POST['artist_id'];
    $genre_id = (int)$_POST['genre_id']; 

    if (!empty($title)) {
        // Insert statement matching your schema fields
        $insert_query = "INSERT INTO song (title, release_date, cover_image_url, audio_url, artist_id, genre_id, play_count) 
                         VALUES ('$title', '$release_date', '$cover_image_url', '$audio_url', $artist_id, $genre_id, 0)";
        
        if (mysqli_query($conn, $insert_query)) {
            $message = "<p style='color:green; font-weight:bold;'>Song added successfully!</p>";
        } else {
            $message = "<p style='color:red; font-weight:bold;'>Error: " . mysqli_error($conn) . "</p>";
        }
    } else {
        $message = "<p style='color:red; font-weight:bold;'>Title field is required.</p>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Song</title>
</head>
<body style="font-family:Arial;background:#f5eee8;">

<div style="width:700px;margin:30px auto;background:white;padding:25px;">
    <h1>Song Management</h1>
    <p>
        <a href="index.php">Homepage</a> |  
        <a href="view_songs.php">My Songs</a> | 
        <a href="logout.php">Logout</a>
    </p>

    <?php echo $message; ?>

    <!-- ADD NEW SONG FORM SECTION -->
    <div style="background:#f9f9f9; padding:20px; border:1px solid #ccc;">
        <h3>Add New Song</h3>
        <form method="post" action="">
            <!-- Artist ID stays hidden behind the scenes -->
            <input type="hidden" name="artist_id" value="1">

            <table cellpadding="5" style="border:none; width:100%;">
                <tr>
                    <td style="width:150px;">Title:</td>
                    <td><input type="text" name="title" required style="width:100%; box-sizing:border-box;"></td>
                </tr>
                <tr>
                    <td>Genre:</td>
                    <td>
                        <select name="genre_id" required style="width:100%; padding:5px; box-sizing:border-box;">
                            <option value="">-- Select a Genre --</option>
                            
                            <!-- BAGUIO CITY MUSIC GENRES OPTION GROUP -->
                            <optgroup label="Baguio City & Cordillera Highlights">
                                <option value="1">Country Music (Baguio Cowboy Classics)</option>
                                <option value="2">Folk / Acoustic Rock (Session Road Folkhouse Style)</option>
                                <option value="3">Cordilleran Traditional (Salidummay / Badiw)</option>
                                <option value="4">Pinoy Indie / Alternative Pop (Cup of Joe Vibes)</option>
                            </optgroup>

                            <!-- LIVE DATABASE GENRES OPTION GROUP -->
                            <optgroup label="Other Database Genres">
                                <?php
                                // Dynamic query matching your database columns: genre_id and genre_name
                                $genre_query = mysqli_query($conn, "SELECT genre_id, genre_name FROM genre ORDER BY genre_name ASC");
                                while ($genre = mysqli_fetch_assoc($genre_query)) {
                                    // Avoid repeating hardcoded IDs if they are already in the DB
                                    if(in_array($genre['genre_id'], [1, 2, 3, 4])) continue;
                                    
                                    echo "<option value='" . $genre['genre_id'] . "'>" . htmlspecialchars($genre['genre_name']) . "</option>";
                                }
                                ?>
                            </optgroup>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Release Date:</td>
                    <td><input type="date" name="release_date" required></td>
                </tr>
                <tr>
                    <td>Cover Image URL:</td>
                    <td><input type="text" name="cover_image_url" placeholder="images/cover.jpg" style="width:100%; box-sizing:border-box;"></td>
                </tr>
                <tr>
                    <td>Audio URL:</td>
                    <td><input type="text" name="audio_url" placeholder="audio/song.mp3" style="width:100%; box-sizing:border-box;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td><button type="submit" name="add_song" style="padding:7px 15px; background:#4CAF50; color:white; border:none; cursor:pointer;">Save Song</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>

</body>
</html>
