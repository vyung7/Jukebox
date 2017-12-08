<?php
require("methods.php");
$onload = "";
$db = connect();
$pName = "Sample Playlist";

$songs = getSongs($db, $pName);
if ($songs){
	foreach ($songs as &$s){
		$title = $s[1];
		$onload .= "addNewSong(\"$title\"); ";
	}
}

$db->close();

$body = <<<BODY
    <!DOCTYPE html>
    <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <link rel="stylesheet" type="text/css" href="style.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link href='https://fonts.googleapis.com/css?family=Codystar' rel='stylesheet'>
            <link href='https://fonts.googleapis.com/css?family=Lobster Two' rel='stylesheet'>
            <link href='https://fonts.googleapis.com/css?family=Marvel' rel='stylesheet'>

            <script type="text/javascript" src="https://www.youtube.com/iframe_api"></script>
            <script>
                var player;
                window.onload = function(){
                    $onload
                }
                function onYouTubeIframeAPIReady() {

                    var ctrlq = document.getElementById("youtube-audio");
                    ctrlq.innerHTML = '<img id="youtube-icon" style="height:30px; vertical-align:middle; align: center;" src=""/><div id="youtube-player"></div>';
                    ctrlq.style.cssText = 'cursor:pointer;cursor:hand;display:none';
                    ctrlq.onclick = toggleAudio;

                    player = new YT.Player('youtube-player', {
                    height: '0',
                    width: '0',
                    //  videoId: ctrlq.dataset.video,
                    playerVars: {
                        autoplay: ctrlq.dataset.autoplay,
                        loop: ctrlq.dataset.loop,
                        list: 'PL_Cqw69_m_yzbMVGvQA8QWrL_HdVXJQX7',
                        listType: 'playlist',
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                        }
                    });
                }

                function togglePlayButton(play) {
                    document.getElementById("youtube-icon").src = play ? "stop.png" : "play.png";
                }

                //    --Hide play/pause button--
                //    function togglePlayButton(play) {
                //        document.getElementById("youtube-icon").src = play ? "" : "";
                //    }

                function toggleAudio() {
                    if ( player.getPlayerState() == 1 || player.getPlayerState() == 3 ) {
                        player.pauseVideo();
                        togglePlayButton(false);
                    } else {
                        player.playVideo();
                        togglePlayButton(true);
                    }
                }

                function onPlayerReady(event) {
                    player.setPlaybackQuality("small");
                    document.getElementById("youtube-audio").style.display = "inline-block";
                    togglePlayButton(player.getPlayerState() !== 5);
                }

                function onPlayerStateChange(event) {
                    if (event.data === 0) {
                        togglePlayButton(false);
                    }
                }

            	var songCount =0;
                function addSong(){
                    var song = prompt("URL", "Enter YouTube Link");
                    addNewSong(song);
                }
            	function addNewSong(song) {
                    if (song != null && song != "") {
                        songCount = songCount +1;
                        var currCount = songCount;
                        var box = document.createElement('div');
                        var listItem = document.createElement('li');

                        box.className = "list-item-container";
                        listItem.id = "song" + currCount;

                        var heartIcon = document.createElement('i');
                        heartIcon.className ="fa fa-heart-o";
                        heartIcon.style="color: black; font-size: 30px; margin-right:7px; line-height: 50px";
                        heartIcon.id = "icon" + currCount;
                        heartIcon.onclick = function() {toggleColor(heartIcon, "#f4428f")};
                        box.appendChild(heartIcon);

                        var node = document.createTextNode("Song" + currCount);
                        box.appendChild(node);

                        var downIcon = document.createElement('i');
                        downIcon.className="fa fa-angle-double-down";
                        downIcon.style="color: black; font-size: 35px; position: absolute; right: 40px; line-height: 50px;";
                        downIcon.id = "down" + currCount;
                        downIcon.onclick = function() {toggleColor(downIcon, "red")};
                        box.appendChild(downIcon);

                        var upIcon = document.createElement('i');
                        upIcon.className="fa fa-angle-double-up";
                        upIcon.style="color: black; font-size: 35px; position: absolute; right: 10px; line-height: 50px;";
                        upIcon.id = "up" + currCount;
                        upIcon.onclick = function() {toggleColor(upIcon, "green")};
                        box.appendChild(upIcon);

                        listItem.appendChild(box)
                        var element = document.getElementById("addNew");
                        element.appendChild(listItem);
                    }
            	}

            	function toggleColor(iconName, color) {
            		if (iconName.style.color === "black"){
            			iconName.style.color = color;
            		}
            		else{
            			iconName.style.color = "black";
            		}
            	}
            </script>
        </head>

        <body>
			<section class="container header">
				<h1>JUKEBOX</h1>
				<h2>Currently Playing</h2>	
			</section>
					
        	<section class="container list item-list">
                <ul>
                <li>
                    <div data-video="pCdWnY4Dn2w" data-autoplay="1" data-loop="1" id="youtube-audio"></div>
                    <span>BTS (防弾少年団) 'MIC Drop -Japanese ver.-' Official MV</span>
                    <a class="icon-button"><i class="fa fa-close"></i></a>
                </li>
                </ul>
            </section>

            <section class="container header">
                <h2>Up Next
                <a href="#"><i class="fa fa-plus-square-o" onclick=addSong() style="color: #606060; font-size: 40px;"></i>
                </a>
                </h2>  
            </section>

			<section class="container list item-list">
                <ul id="addNew">
                </ul>
            </section>
        	</br>

            <h2> Room Code: XXXXXX </h2>

                <section class="container nav">
                    <center>
                        <a class="icon" href="Home.html"><i class="fa fa-home"></i></a>
                        <a class="icon" href="Playlists.php"><i class="fa fa-list"></i></a>
                        <a class="icon" href="Currently Playing.php"><i class="fa fa-play" style="color: #FFFFFF;"></i></a>
                    </center>
                </section>
        </body>
    </html>
BODY;

echo $body;
?>
