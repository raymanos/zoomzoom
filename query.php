<?php
header('Content-Type: text/html;charset=utf-8');
include "connect_db.php";
include "func.php";

function getClearFilename($filename){
	$filename = iconv("utf8","windows-1251",$filename);
	$filename = urlencode($filename);
	$filename = preg_replace("/[+]/","%20",$filename);
	$filename = preg_replace("/%2F/","/",$filename);
	return $filename;
 }
function playlistExists($id_user,$name_pls){
	$sql = "select name from user_playlist where id_user = '$id_user' and name='$name_pls'";
	// echo $sql;
	$qr = mysql_query($sql) or die(mysql_error());
	$result = mysql_num_rows($qr);
	// echo "$result";
	if($result > 0){
		return true;
	}
	else{
		return false;
	}
 }
function getNamePlsByID($id_user, $name){
	$sql = "select id from user_playlist where id_user = '$id_user' and name = '$name'";
	$qr = mysql_query($sql) or die(mysql_error());
	if( mysql_num_rows($qr) > 0 )
	{
		$res = mysql_fetch_assoc($qr);
		$id = $res["id"];
	}
	else
		$id = -1;
	return $id;
 }
// Добавляем каталог в базу
if($_POST["action"] == "add_folder"){
	$path = $_POST["path"];
	if(addFolder($path))
		echo "true";
	else
		echo "false";
 }
if($_POST["action"] == "get_count"){
	$id_user  = $_POST["id_user"];
	$id_track = $_POST["id_track"];
	$sql = "select my.count myCount,allUsers.count allCount
			from
			(select count from counts where id_user = '$id_user' and id_track = '$id_track') my,
			(select sum(count) count  from counts where id_track = '$id_track') allUsers";
	$res = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($res);
	// if(count($row)>0)
	// $myCount  = $row["myCount"];
	// $allCount = $row["allCount"];
	// echo "get_count";
	// print_r($row);
	// echo "row:".$row;
	echo json_encode($row);

 }
if($_POST["action"] == "set_settings"){
	$id_user = $_POST["id_user"];
	$volume  = $_POST["volume"];
	echo setSettings($id_user,$volume);
 }
if($_POST["action"] == "get_settings"){
	$id_user = $_POST["id_user"];
	$qr = mysql_query("select volume from settings where id_user = '$id_user'") or die(mysql_error());
	$res = mysql_fetch_assoc($qr);
	$volume = $res["volume"];
	echo $volume;
 }

if($_POST["action"] == "update_genre" ){
	$user = $_POST["user"];
	$genre = $_POST["genre"];
	$sql = "select genre from ".$user."_genres where genre = '$genre'";
	$count = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($count) == 0){
		$sql = "insert into ".$user."_genres (genre,genre_count) value ($genre','1')";
		mysql_query($sql) or die(mysql_error());
	}
	else{
		$qr = mysql_query("select genre_count from ".$user."_genres where genre = '$genre'") or die(mysql_error());
		$count = mysql_fetch_assoc($qr)["genre_count"];
		$count++;
		echo "Old genre count: $count";
		$sql = "update ".$user."_genres set genre_count = '$count' where genre = '$genre'";
		mysql_query($sql) or die(mysql_error());
	}
	echo "true_genre";
 }
if($_POST["action"] == "inc_count" ){
	$id_track = $_POST["id_track"];
	$id_user  = $_POST["id_user"];
	$last_date  = date("Y-m-d H:i:s");
	$sql = "select id_track from counts where id_track = '$id_track' and id_user = '$id_user'";

	$countN = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($countN) == 0){
		$sql = "insert into counts (id_track,id_user,count,last_date) value ('$id_track','$id_user','1','$last_date')";
		mysql_query($sql) or die(mysql_error());
	}
	else{
		echo "@2";
		$qr = mysql_query("select count from counts where id_track = '$id_track' and id_user = '$id_user'") or die(mysql_error());
		$count = mysql_fetch_assoc($qr)["count"];
		$count++;
		// echo "Old count: $count";
		$sql = "update counts set count = '$count',last_date = '$last_date' where id_track = '$id_track' and id_user = '$id_user'";
		mysql_query($sql) or die(mysql_error());
	}
	echo "true";
 }
 
if($_POST["action"] == "get_stars" ){
	$stars = 0;
	$id_track = $_POST["id_track"];
	$id_user  = $_POST["id_user"];
	$sql = "select star from rating where id_track = $id_track and id_user = $id_user";
	$qr = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($qr);
	if($row["star"] != "")
		echo $row["star"];
	else
		echo "-1";
 }
if($_POST["action"] == "set_stars" ){
	$id_track   = $_POST["id_track"];
	$star       = $_POST["star"];
	$id_user    = $_POST["id_user"];
	$last_date  = date("Y-m-d H:i:s");
	$sql = "select id_track from rating where id_track = '$id_track' and id_user = '$id_user'";
	$count = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($count) == 0){
		$sql = "insert into rating (id_user,id_track,star,last_date) value ('$id_user','$id_track','$star','$last_date')";
		mysql_query($sql) or die(mysql_error());
	}
	else{
		$sql = "update rating set star = '$star' where id_track = '$id_track' and id_user = '$id_user'";
		mysql_query($sql) or die(mysql_error());
	}
	echo "true";
 }

if($_POST["action"] == "get_artists"){
	$data = "";
	$genre = $_POST["genre"];
	$sql = "select distinct artist from music where genre = '$genre' order by artist desc";
	$qr = mysql_query($sql) or die(mysql_error());
	if(!$qr){
		echo "false";
	}
	else{
		while($row = mysql_fetch_assoc($qr)){
			$data .= $row["artist"].",";
		}
		$data = substr($data,0,strlen($data)-1);
		echo $data;
	}
 }
if($_POST["action"] == "get_album"){
	$data = array();
	$artist = mysql_real_escape_string($_POST["artist"]);
	// echo "333";
	$sql = "select distinct albums,year from music where artist = '$artist'";
	$qr = mysql_query($sql) or die(mysql_error());
	if(!$qr){
		echo "false";
	}
	else{
		$i = 0;
		while($row = mysql_fetch_assoc($qr)){
			// $data .= $row["albums"].",";     <--- так было
			$data[$i]["album"] = $row["albums"];
			$data[$i]["year" ] = $row["year"];
			// $data[$row["albums"]] = $row["year"];
			$i++;
			
		}
		// $data = substr($data, 0, strlen($data)-1);
		// echo "$data";
		echo json_encode($data);
		// print_r($data);
	}
 }
if($_POST["action"] == "pls_exists"){
	$name = $_POST["name"];
	$id_user = $_POST["id_user"];
	if( playlistExists($id_user,$name) ){
		echo "true";
	}
	else{
		echo "false";
	}
 }
 // получаем все имена плейлистов 
if($_POST["action"] == "get_name_pls"){
	$id_user  = $_POST["id_user"];

	$sql = "select name,social from user_playlist where id_user = '$id_user' order by social";
	$qr = mysql_query($sql) or die(mysql_error());
	$array_pls = array();
	$i = 0;
	while($row = mysql_fetch_assoc($qr)){
		$array_pls[$i]["name"  ] = $row["name"];
		$array_pls[$i]["social"] = $row["social"];
		$i++;
	}
	$str = json_encode($array_pls);
	echo $str;
 }
if($_POST["action"] == "del_pls"){
	$id_user = $_POST["id_user"];
	$name    = $_POST["name"];
	$sql = "delete from playlists where id in (
				select * from (
					select p.id 
						from user_playlist up
						join playlists p on up.id = p.id_pls  
					where p.id_user = '$id_user' and name = '$name'
				) as t
			)";
			
	$qr = mysql_query($sql) or die(mysql_error());
	if($qr){
		$sql = "delete from user_playlist where id_user = '$id_user' and name = '$name'";
		$qr = mysql_query($sql) or die(mysql_error());
		if($qr)
			echo "true";
		else
			echo "false";
	}
	else{
		echo "false";
	}
 }
if($_POST["action"] == "load_pls"){
	$id_user = $_POST["id_user"];
	$i = 0;
	$name = $_POST["name"];
	$id_pls = getNamePlsByID($id_user,$name);
	$data_json[0]["id"] = $data_json[0]["artist"] = $data_json[0]["albums"] = $data_json[0]["tracks"] = $data_json[0]["filename"] = 0;
	$data_json[0]["cover"] = $data_json[0]["genre"] = 0;
	$sql = "select m.id,m.artist,m.albums,m.tracks,m.filename,m.cover,m.genre
				from playlists pl 
				join user_playlist up on up.id = pl.id_pls
				join music m on m.id = pl.id_track
			where up.name='$name' and up.id_user = '$id_user'";
	$qr = mysql_query($sql) or die(mysql_error());
	while( $row = mysql_fetch_assoc($qr) ){
		$data_json[$i]["id_track"]       = $row["id"];
		$data_json[$i]["artist"]   = $row["artist"];
		$data_json[$i]["album"]    = $row["albums"];
		$data_json[$i]["title"]    = $row["tracks"];
		//декодируем имя файла
		$URL = $row['filename'];
		$URL = urlencode($URL);
		$URL = preg_replace("/[+]/","%20",$URL);
		$URL = preg_replace("/%2F/","/",$URL);
		$data_json[$i]["filename"] = $URL;
		// $data_json[$i]["filename"] = getClearFilename($row["filename"]);
		$data_json[$i]["cover"]    = getClearFilename($row["cover"]);
		$data_json[$i]["genre"]    = $row["genre"];
		$i++;
	}
	// if($data_json)
	$data = json_encode($data_json);
	echo $data;
 }
if($_POST["action"] == "save_pls"){
	$id_user = $_POST["id_user"];
	$data_json = $_POST["data"];//name,id_track <- их нужно записать в таблицу user_playlists
	$name = $_POST["name"];
	if($_POST["social"] == "on")
		$social = 1;
	else
		$social = 0;

	$data = json_decode($data_json);
	$error = json_last_error();
	$count = count($data);
	$last_date  = date("Y-m-d H:i:s");
	// Записываем название плейлиста
	$sql = "insert into user_playlist (id_user,name,date,social,social_ratng) values ('$id_user','$name','$last_date',$social,'0')";
	$qr = mysql_query($sql) or die(mysql_error());
	$id = getNamePlsByID($id_user,$name);
	if($id != -1)
	{
		// echo "data: $data_json";
		for($i=0; $i < $count; $i++){
			$id_track = $data[$i]->id;
			// Теперь пишем конкретные треки в таблицу playlists
			$sql = "insert into playlists (id_pls,id_user,id_track,date) values ('$id','$id_user','$id_track','$last_date')";
			$qr = mysql_query($sql) or die(mysql_error());
	 	}
		echo "true";
	}
	else
	{
		echo "false";
	}
 }
if($_POST["action"] == "get_tracks"){
	$dataMusic = array();
	$album  = $_POST["album"];
	$artist = $_POST["artist"];
	$sql = "select  id,tracks,artist,filename,cover,genre,year from music where albums = '$album' and artist = '$artist'";
	$qr = mysql_query($sql) or die(mysql_error());
	if(!$qr){
		echo "false";
	}
	else{
		$i = 0;
		while($row = mysql_fetch_assoc($qr)){
			//декодируем имя файла
			$f = $row['filename'];
			$URL = $f;
			// $URL = iconv("utf8","windows-1251",$URL);
			$URL = urlencode($URL);
			$URL = preg_replace("/[+]/","%20",$URL);
			$URL = preg_replace("/%2F/","/",$URL);

			$cover = $row["cover"];
			// $cover = iconv("utf8","windows-1251",$cover);
			$cover = urlencode($cover);
			$cover = preg_replace("/[+]/","%20",$cover);
			$cover = preg_replace("/%2F/","/",$cover);

			$dataMusic[$i]["id_track"] = $row["id"];
			$dataMusic[$i]["artist"]   = $artist;
			$dataMusic[$i]["album"]    = $album;
			$dataMusic[$i]["title"]    = $row["tracks"];
			$dataMusic[$i]["filename"] = $URL;
			$dataMusic[$i]["cover"]    = $cover;
			$dataMusic[$i]["year"]     = $row["year"];
			$dataMusic[$i]["genre"]    = $row["genre"];
			$i++;
		}
		$str = json_encode($dataMusic);
		echo $str;
	}
 }
?>