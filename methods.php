<?php
function connect(){
    $db = new mysqli("localhost", "", "");
    if ($db->connect_error) {
        die("Connection failed ".$db->connect_error);
        exit();
    }
    $db->query("use jb_playlists");
    return $db;
}

//Creates a playlist with the given name
function createPlaylist($db, $name){
    $query = "create table if not exists `$name` ( Link varchar(256) not null, Title varchar(50) not null, Upvotes int, Downvotes int, TrackNum int ) ";
    if (!$db->query($query)){
        echo "Error creating playlist '$name': ".$db->error;
    }
}

//Given a database, will use playlistButton to return the HTML containing all of the playlist buttons
function getPlaylists($db){
    $query = "show tables from jb_playlists";
    $result = $db->query($query);
    $output = "";

    if ($result){
        while($name = mysqli_fetch_row($result)){
            $output .= playlistButton($name[0]);
        }
        return $output;
    }else{
        return "";
    }
}

//Returns the HTML code for a playlist button with the given name
function playlistButton($name){
    return "<a class='a hBox' href='EditPlaylist.php?playlistName=$name'>$name<i class='fa fa-angle-right' style='font-size: 30px; float: right;'></i></a>";
}

//Adds a song with the given information to the given playlist on the database
function addSong($db, $playlist, $link, $title){
    $num = playlistLength($db, $playlist);
    if ($num == -1){
        echo "Error getting length of playlist $playlist<br>";
        return;
    }

    //Grabs the video title based on the YouTube URL passed in to this function
    $api_key = 'AIzaSyBeULimNCbH3fmwTNFBzP2mOFnWqS9Sefo';
    $url_parts = explode("watch?v=", "https://www.youtube.com/watch?v=rRzxEiBLQCA");
    $videoId = $url_parts[1];
    $api_url = 'https://www.googleapis.com/youtube/v3/videos?id='. $videoId . '&key=' . $api_key . '&fields=items(snippet(title))&part=snippet';
    $videoTitleObject = json_decode(file_get_contents($api_url)); 
    $videoTitle = $videoTitleObject->items[0]->snippet->title;

    // echo $playlistInfo;   

    $query = "insert into `$playlist` values ('$link', '$title', 0, 0, $num)";
    if (!$db->query($query)){
        echo "Error adding $title to $playlist: ".$db->error."<br>";
    }

}

function getSongs($db, $playlist){
    $query = "select * from `$playlist` order by TrackNum";
    $result = $db->query($query);
    if ($result){
        $output = array();
        while($row = mysqli_fetch_row($result)){
            $output[] = $row;
        }
        $result->close();
        return $output;
    }else{
        // echo "Couldn't get songs from $playlist".$db->error;
    }
}

function playlistLength($db, $playlist){
    $query = "select * from `$playlist`";
    $result = $db->query($query);
    if ($result){
        return $result->num_rows;
    }else{
        echo $db->error;
        return -1;
    }
}

function genPage($title, $body){
    return "<!DOCTYPE html>
    <html>
    <head>
        <meta name='$title' content='width=device-width, initial-scale=1'>
        <link rel = 'stylesheet' type = 'text/css' href = 'style.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    </head>
    <body>
    $body
    </html>";
}

?>