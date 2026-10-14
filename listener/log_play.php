<?php
session_start();
include '../dbconn.php';

// Block non-authorized actors
if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "listener") {
    exit("Unauthorized entry point.");
}

if (isset($_POST['song_id']) && isset($_POST['audio_url'])) {
    $song_id = (int)$_POST['song_id'];
    $audio_url = $_POST['audio_url'];
    
    // Up-ticks the native 'play_count' metric found on your table schema map
    mysqli_query($conn, "UPDATE song SET play_count = play_count + 1 WHERE song_id = $song_id");
    
    // Smoothly pass-through straight to the asset source path
    header("Location: ../" . $audio_url);
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
