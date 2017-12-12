<?php
//Songs database - Contains 1 table per playlist, using the room code as the name of the table
//Each entry contains the videoId/link as the primary key, the title, the number of skips, and the track number

//Playlists table
//Each entry corresponds to a playlist, using the room code as the primary key, with the name and skip % as well

//Users database - Contains 1 table per user, using the username as the name of the table
//Each entry corresponds to the room code of the playlist the user owns

/*-Basic Methods-*/

//returns the connected-to songs database
function connectSongs(){
    $db = new mysqli("localhost", "", "");
    if ($db->connect_error) {
        die("Connection failed ".$db->connect_error);
        exit();
    }
    $db->query("use jb_songs");
    return $db;
}

//returns the connected-to playlists database
function connectPlaylists(){
    $db = new mysqli("localhost", "", "");
    if ($db->connect_error) {
        die("Connection failed ".$db->connect_error);
        exit();
    }
    $db->query("use jb_playlists");
    return $db;
}

//returns the connected-to users database
function connectUsers(){
    $db = new mysqli("localhost", "", "");
    if ($db->connect_error) {
        die("Connection failed ".$db->connect_error);
        exit();
    }
    $db->query("use jb_users");
    return $db;
}

//songs - creates a new empty playlist table in the songs database, named after the room code
//playlists - inserts a playlist into the table
//users - inserts a new playlist with the given room code
function createPlaylist($db_songs, $db_playlists, $db_users, $username, $name, $roomCode, $skip){
    $query = "create table if not exists `$roomCode` ( Link varchar(256) not null, Title varchar(50) not null, Upvotes int, Downvotes int, TrackNum int ) ";
    if (!$db_songs->query($query)){
        echo "Error creating playlist '$name': ".$db_songs->error;
        return;
    }
    $query = "insert into `playlists` values ('$roomCode', '$name', $skip)";
    if (!$db_playlists->query($query)){
        echo "Error creating playlist '$name': ".$db_playlists->error;
        return;
    }
    $query = "insert into `$username` values ('$roomCode')";
    if (!$db_users->query($query)){
        echo "Error creating playlist '$name': ".$db_users->error;
        return;
    }
}

//songs - removes the table with the given room code
//playlists - removes a playlist with the given room code from the table
//users - removes a playlist with the given room code
function removePlaylist($db_songs, $db_playlists, $db_users, $username, $roomCode){
    $query = "drop table if exists `$roomCode`";
    if (!$db_songs->query($query)){
        echo "Error removing playlist with room code '$roomCode': ".$db_songs->error;
        return;
    }
    $query = "delete from `playlists` where roomCode=`$roomCode`";
    if (!$db_playlists->query($query)){
        echo "Error removing playlist with room code '$roomCode': ".$db_playlists->error;
        return;
    }
    $query = "delete from `$username` where roomCode=`$roomCode`";
    if (!$db_users->query($query)){
        echo "Error removing playlist with room code '$roomCode': ".$db_users->error;
        return;
    }
}

/*-Songs Methods-*/

//returns the number of songs/rows in the playlist/table
function playlistLength($db_songs, $playlist){
    $query = "select * from `$playlist`";
    $result = $db_songs->query($query);
    if ($result){
        return $result->num_rows;
    }else{
        echo $db_songs->error;
        return -1;
    }
}

//Returns an array containing all of the rows of the playlist corresponding to the given room code
function getSongs($db_songs, $roomCode){
    $query = "select * from `$roomCode` order by TrackNum";
    $result = $db_songs->query($query);
    if ($result){
        $output = array();
        while($row = mysqli_fetch_row($result)){
            $output[] = $row;
        }
        $result->close();
        return $output;
    }else{
        echo "Couldn't get songs from $roomCode".$db_songs->error;
        return array();
    }
}

//addSong - adds a song to the given playlist
function addSong($db_songs, $roomCode, $songQuery){
    $num = playlistLength($db_songs, $roomCode);
    if ($num == -1){
        echo "Error getting length of playlist $roomCode<br>";
        return;
    }

    //Grabs the video title and ID based on the YouTube query passed in to this function
    $api_key = 'AIzaSyBeULimNCbH3fmwTNFBzP2mOFnWqS9Sefo';
    $api_url = 'https://www.googleapis.com/youtube/v3/search?part=snippet&maxResults=1&q='. 'bts' . '&type=video&fields=items&key=' . $api_key;
    $searchResultsObject = json_decode(file_get_contents($api_url));
    $videoId = $searchResultsObject->items[0]->id->videoId;
    $videoTitle = $searchResultsObject->items[0]->snippet->title;

    $query = "insert into `$roomCode` values ('$videoId', '$videoTitle', 0, 0, $num)";
    if (!$db_songs->query($query)){
        echo "Error adding $title to playlist with room code $roomCode: ".$db_songs->error."<br>";
    }
}

//removeSong - removes the song from the given playlist with the given track number
function removeSong($db_songs, $roomCode, $index){
    $query = "delete from `$roomCode` where TrackNum=$index";
    if (!$db_songs->query($query)){
        echo "Error removing playlist with room code '$roomCode': ".$db_songs->error;
        return;
    }
}

/*-Playlist Methods-*/

//returns the row corresponding to the given room code
function getPlaylist($db_playlists, $roomCode){
    $query = "select * from `playlists`";
    $result = $db_playlists->query($query);
    if ($result){
        $output = array();
        while($row = mysqli_fetch_row($result)){
            $output[] = $row;
        }
        $result->close();
        return $output;
    }else{
        echo "Couldn't get playlists from database".$db_playlists->error;
        return array();
    }
}

/*-Users Methods-*/

function addUser($db_users, $username){
    $query = "create table if not exists `$username` ( Playlist varchar(256) not null ) ";
    if (!$db_users->query($query)){
        echo "Error creating user '$username': ".$db_users->error;
        return;
    }
}

//returns an array of all of the room codes of the user's playlists
function getUserPlaylists($db_users, $username){
    $query = "select * from `$username`";
    $result = $db_users->query($query);
    if ($result){
        $output = array();
        while($row = mysqli_fetch_row($result)){
            $output[] = $row[0];
        }
        $result->close();
        return $output;
    }else{
        echo "Couldn't get playlists from database".$db_users->error;
        return array();
    }
}

//Returns the HTML code for a playlist button with the given name
function playlistButton($name){
    return "<a class='a hBox' href='EditPlaylist.php?playlistName=$name'>$name<i class='fa fa-angle-right' style='font-size: 30px; float: right;'></i></a>";
}

//Returns the HTML code for all playlists from a given user
function getPlaylistButtons($db_users, $username){
    $playlists = getUserPlaylists($db_users, $username);
    $result = "";
    foreach ($playlists as &$p){
        $result .= playlistButton($p);
    }
    return $result;
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
