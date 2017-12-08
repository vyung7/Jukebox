<?php
require("methods.php");
$db = connect();
$playlists = getPlaylists($db);

$db->close();

$top = <<<TOP
<head>
	<link href='https://fonts.googleapis.com/css?family=Codystar' rel='stylesheet'>
	<link href='https://fonts.googleapis.com/css?family=Lobster Two' rel='stylesheet'>
</head>
<body>
	<section class="container header">
		<h1>JUKEBOX</h1>
		<h2>Playlist</h2>	
	</section>

	<center>
TOP;

$bottom = <<<BOTTOM
</center>

<center>
	<a class="a hBox" href="Edit Playlist.php" style="margin-top:30px">Liked Songs<i class="fa fa-angle-right" style="font-size: 30px; float: right;"></i></a>
</center>

	<section class="container nav">
		<center>
			<a class="icon" href="Home.html"><i class="fa fa-home" ></i></a>
			<a class="icon" href="Playlists.php"><i class="fa fa-list" style="color: #FFFFFF;"></i></a>
			<a class="icon" href="Currently Playing.php"><i class="fa fa-play"></i></a>
		</center>
	</section>
</body>
BOTTOM;

echo genPage("Playlists", $top.$playlists.$bottom);

?>
