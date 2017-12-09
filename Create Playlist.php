<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="style.css">
		<link href='https://fonts.googleapis.com/css?family=Codystar' rel='stylesheet'>
		<link href='https://fonts.googleapis.com/css?family=Lobster Two' rel='stylesheet'>
		<link href='https://fonts.googleapis.com/css?family=Marvel' rel='stylesheet'>

		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
		<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<script>
			$(function() {
				$( "#sortable-1" ).sortable();
				$( "#addNew" ).sortable();
			});
		</script>
		<script>
			var songs = ["BTS (防弾少年団) 'MIC Drop -Japanese ver.-' Official MV",
						"BTS (방탄소년단) 'DNA' Official MV",
						 "BTS (방탄소년단) 'Not Today' Official MV",
						  "BTS (방탄소년단) '피 땀 눈물 (Blood Sweat & Tears)' Official MV",
						  "[MV] BTS(방탄소년단) _ FIRE (불타오르네)"
						 ];

			var songCount = -1;
			function addNewSong() {
				songCount = songCount + 1;
				var currCount = songCount;
				var icon = document.createElement('i');
				icon.className ="fa fa-reorder";
				icon.style = "color:black; font-size: 20px; padding-right:5px";
				icon.id = "icon" + currCount;
				var box = document.createElement('div');
				var listItem = document.createElement('li');
				box.className = "list-item-container";
				// var node = document.createTextNode("Song" + currCount);
				var node = document.createTextNode(songs[currCount])
				box.appendChild(node);
				listItem.id = "song" + currCount
				var close = document.createElement('i');
				close.className = "fa fa-close";
				close.id = "delete";
				close.style = "position: absolute; right: 20px; line-height: 50px";
				close.onclick = function() {removeSong(currCount)};
				box.appendChild(close);
				listItem.appendChild(icon);
				listItem.appendChild(box);
				var element = document.getElementById("addNew");
				element.appendChild(listItem);
			}

			function removeSong(songCount) {
				var parent = document.getElementById("addNew");

				var child = document.getElementById("song"+ songCount);
				var iconChild = document.getElementById("icon"+ songCount);

				parent.removeChild(child);
				parent.removeChild(iconChild);
			}

			function removePlaylist() {
				if (confirm("Are you sure you want to delete this playlist?")) {
					window.location.href = "Home.html"
					//will remove all instances of playlist
				}
			}
		</script>
	</head>

	<body>
		<section class="container header">
			<h1>JUKEBOX</h1>
			<h2>Create Playlist</h2>	
		</section>

		<section class="container list form-list">
			<ul>
				<form action="/action_page.php">
				<li>
					<label>Name:</label>
					<input type="text" placeholder="Enter Playlist Name">
					<a class="icon-button" onclick="removePlaylist()">
                        <i class="fa fa-trash-o"></i>
                    </a>
				</li>
				</form>
				<form action="/action_page.php">
				<li>
					<label>URL:</label>
					<input type="text" placeholder="Enter Youtube URL">
					<a class="icon-button" onclick="addNewSong()">
                        <i class="fa fa-plus-square-o"></i>
                    </a>
				</li>
				</form>
			</ul>
		</section>

		<section class="container list item-list">
			<ul id="addNew">
			</ul>
		</section>

		<section class="container nav">
			<center style="background-color: #D0EAFF; padding-bottom: 10px; padding-top: 10px;">
				<a href="Playlists.php"><i class="fa fa-check-circle-o" style="font-size: 40px; "></i></a>
				<a href="Playlists.php"><i class="fa fa-times-circle-o" style="font-size: 40px; padding-left:20px"></i></a>
			</center>

			<center>
				<a class="icon" href="Home.html"><i class="fa fa-home"></i></a>
				<a class="icon" href="Playlists.php"><i class="fa fa-list"></i></a>
				<a class="icon" href="Currently Playing.php"><i class="fa fa-play"></i></a>
			</center>
		</section>
	</body>
</html>
