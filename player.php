<?php header('Content-Type: text/html;charset=utf-8'); ?>
<html>
<head>
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/listbox.js"></script>
	<script type="text/javascript" src="js/script.js" ></script>
	<script type="text/javascript" src="js/lastfm.api.cache.js" ></script>
	<script type="text/javascript" src="js/lastfm.api.js"></script>
	<script type="text/javascript" src="js/lastfm.api.md5.js"></script>
	<link rel="stylesheet" href="css/listbox.css" media="screen">
 	<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/style.css" media="screen">
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
<div id="wrapper">
<div id="panel-left" class="mystyle"></div>
<div id="content">
	<div id="audio-player" class="mystyle">
		<div id="up-div">
			<div id="audio-controls">
				<img id="player-prev" src="img/prev.png" />
				<img id="player-play" src="img/play2.png" />
				<img id="player-next" src="img/next.png" />
			</div>
			<div id="audio-info" class="shadowtext">
				<b> - </b>
			</div>	
			<div id="volume-slider"></div>
		</div>
		<div id="down-div">
			<div id="audio-numbers" class="shadowtext">0:00 / -:--</div>
			<div id="audio-slider"></div>
			<div id="user_info" id_user="<?php echo getIDUser($_SESSION["login"])?>">
				<img id="userpic" src="img/user.png"/>
				<?php echo $_SESSION["login"]; ?>
				<img id="exit" src="img/exit.png" href="exit.php"/>
			</div>
		</div>
	</div>

	<!-- ///////////////////////////////////////////////// -->
	<audio id='audio' name='media' >
		<source src="" type='audio/mpeg' preload='none' autoplay>
	</audio>
	<!-- ///////////////////////////////////////////////// -->
	<div id="tabs">
		<ul>
			<li><a href="#artist-div">Коллекция</a></li>
			<li><a href="#lastfm">Last.FM</a></li>
			<li><a href="#lyrics">Текст</a></li>
		</ul>
		<div id="artist-div">
			<select id="genre-select">
				<?php
					include "genre.php"; 
				?>
			</select>
			<select id="artist-select">
				<?php 
					if($_GET["scan"] == "1"){
      					include "create_db.php";
    				}
					//Заполнение листбокса артистами из базы
					include "connect_db.php";
					include "content.php";
				?>
			</select>
			<select id="albums-select"></select>
			<select id="tracks-select"></select>
		</div>
		<div id="lastfm">Здесь будет Last.FM</div>
		<div id="lyrics">Здесь будут тексты песен</div>
	</div>
	<div id="buttons-div" class="mystyle">
		<img src="img/new_playlist.png" id="button_new_playlist" class="mybutton" />
		<img src="img/new_list.png" id="button_new_list" class="mybutton" />
		<img src="img/del_list.png" id="button_del_playlist" class="mybutton" />
		<img src="img/folder.png" id="button_add_folder" class="mybutton" />
		<!-- <button id="test_button">Test</button> -->
		<div id="playlist-div">
			<select id="playlists">
			</select>
		</div>
	</div>
	<!-- //////////////////////////////////////////////// -->
	<div id='tracks-div' class="mystyle">
		<table id='track-table' class='table-scroll'>
			<tbody></tbody> 
		</table>
	</div>
</div>

<div id="panel-right" class="mystyle">
	<div id="cover-div"><img src="" id="cover"></div>
	<div id="track-info"></div>
</div>
</div>
<div id="dialog-div">
	<input type="text" id="name-pls"><br><br>
	<input type="checkbox" id="social-pls">Социальный плейлист</input>
</div>
<div id="dialog-del-pls"></div>
<div id="dialog-message"></div>
<div id="dialog-add-folder">
	<input type="text" id="path">
</div>
<script src="js/myJSplayer.js"></script>
<script type="text/javascript" src="js/player.js"></script>
</body>
</html>