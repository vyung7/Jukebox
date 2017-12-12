<?php
require("methods.php");
$db = connect();
if ($_GET["playlistName"]){
    createPlaylist($db, $_GET["playlistName"]);
}
header("Location: EditPlaylist.php?playlistName=".$_GET["playlistName"]);
$db->close();
?>
