<?php
    require("methods.php");
    $db = connect();
    createPlaylist($db, "A B C");
    /*addSong($db, "A", "lonk", "song a");
    addSong($db, "A", "lank", "sung b");
    addSong($db, "A", "lunk", "zomb");*/
    $body = "";
    $songs = getSongs($db, "A");
    if ($songs){
        foreach ($songs as &$s){
            $body .= $s["title"];
        }
    }
    genPage("Test", $body);
    $db->close();
?>
