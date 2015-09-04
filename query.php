<?php
header('Content-Type: text/html;charset=utf-8');
include "connect_db.php";

function getClearFilename($filename){
	$filename = iconv("utf8","windows-1251",$filename);
	$filename = urlencode($filename);
	$filename = preg_replace("/[+]/","%20",$filename);
	$filename = preg_replace("/%2F/","/",$filename);
	return $filename;
 }
function playlistExists($user,$name){
	$sql = "select name from ".$user."_playlists where name='$name'";
	// echo $sql;
	$qr = mysql_query($sql);
	$result = mysql_num_rows($qr);
	// echo "$result";
	if($result > 0){
		return true;
	}
	else{
		return false;
	}
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
	$music_id = $_POST["music_id"];
	$user = $_POST["user"];
	$sql = "select music_id from ".$user."_history where music_id = '$music_id'";
	$count = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($count) == 0){
		$sql = "insert into ".$user."_history (music_id,count) value ('$music_id','1')";
		mysql_query($sql) or die(mysql_error());
	}
	else{
		$qr = mysql_query("select count from ".$user."_history where music_id='$music_id'") or die(mysql_error());
		$count = mysql_fetch_assoc($qr)["count"];
		$count++;
		echo "Old count: $count";
		$sql = "update ".$user."_history set count = '$count' where music_id = '$music_id'";
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
	$star      = $_POST["star"];
	$id_user   = $_POST["id_user"];
	$last_date = date("Y-m-d H:i:s");
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
if($_POST["action"] == "love_track"){
	$id = $_POST["id"];
	$user = $_POST["user"];
	//найти есть ли искомый ID, если есть то в него вставляем, если нет то создаем новый
	$sql = "select music_id from ".$user."_history where music_id = '$id'";
	echo $sql;
	$count = mysql_query($sql) or die(mysql_error());
	// Такого ID нет, вставляем новый
	if(mysql_num_rows($count) == 0){
		$sql = "insert into ".$user."_history (music_id,loved) value ('$id','true')";
		mysql_query($sql) or die(mysql_error());
	}
	//есть такой ID, его обновляем
	else{
		$sql = "update ".$user."_history set loved = 'true' where music_id = '$id'";
		mysql_query($sql) or die(mysql_error());
	}
	echo "true";
 }
if($_POST["action"] == "get_artists"){
	$data = "";
	$genre = $_POST["genre"];
	$sql = "select distinct artist from music where genre = '$genre'";
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
	$data = "";
	$artist = mysql_real_escape_string($_POST["artist"]);
	// echo "333";
	$sql = "select distinct albums from music where artist = '$artist'";
	$qr = mysql_query($sql) or die(mysql_error());
	if(!$qr){
		echo "false";
	}
	else{
		while($row = mysql_fetch_assoc($qr)){
			$data .= $row["albums"].",";
			// echo $row['albums'];
		}
		$data = substr($data, 0, strlen($data)-1);
		echo "$data";
	}
 }
if($_POST["action"] == "pls_exists"){
	$name = $_POST["name"];
	$user = $_POST["user"];
	if( playlistExists($user,$name) ){
		echo "true";
	}
	else{
		echo "false";
	}
 }
if($_POST["action"] == "get_name_pls"){
	$user = $_POST["user"];
	$sql = "select distinct name from ".$user."_playlists";
	$qr = mysql_query($sql) or die(mysql_error());
	$array_pls = array();

	while($row = mysql_fetch_assoc($qr)){
		$name = $row["name"];
		$sql = "select id_track from ".$user."_playlists where name='$name'";
		$qr2 = mysql_query($sql) or die(mysql_error());
		while($row2 = mysql_fetch_assoc($qr2)){
			$id_track = $row2["id_track"];
			$array_pls[$name]["ids"][] = $id_track;
		}
	}
	$data = json_encode($array_pls);
	echo $data;
 }
if($_POST["action"] == "del_pls"){
	$user = $_POST["user"];
	$name = $_POST["name"];
	$sql = "delete from ".$user."_playlists where name =	 '$name'";
	$qr = mysql_query($sql) or die(mysql_error());
	if($qr){
		echo "true";
	}
	else{
		echo "false";
	}
 }
if($_POST["action"] == "load_pls"){
	$user = $_POST["user"];
	$data = "";
	$data2 = "[";
	$data_json = [];
	$i = 0;
	$name = $_POST["name"];

	$sql = "select id_track from ".$user."_playlists where name='$name'";
	$qr = mysql_query($sql) or die(mysql_error());
	while($row = mysql_fetch_assoc($qr)){
		$id_track = $row["id_track"];
		// echo $id_track;
		$sql = "select id,artist,albums,tracks,filename,cover,genre from music where id = '$id_track'";
		$qr2 = mysql_query($sql) or die(mysql_error());
		while($row2 = mysql_fetch_assoc($qr2)){
			$id = $row2["id"];
			$artist = $row2["artist"];
			$album = $row2["albums"];
			$title = $row2["tracks"];
			$filename = getClearFilename($row2["filename"]);
			$cover = getClearFilename($row2["cover"]);
			$genre = $row2["genre"];

			$data_json[$i]["id"] = $id;
			$data_json[$i]["artist"] = $artist;
			$data_json[$i]["album"] = $album;
			$data_json[$i]["title"] = $title;
			$data_json[$i]["filename"] = $filename;
			$data_json[$i]["cover"] = $cover;
			$data_json[$i]["genre"] = $genre;

			$i++;

		}
	 }
	$data = json_encode($data_json);
	echo $data;
 }
if($_POST["action"] == "save_pls"){
	$user = $_POST["user"];
	$data_json = $_POST["data"];//name,id_track <- их нужно записать в базу %login%_playlists
	$name = $_POST["name"];
	$data = json_decode($data_json);
	$error = json_last_error();
	$count = count($data);
	// echo "data: $data_json";
	for($i=0; $i < $count; $i++){
		$id_track = $data[$i]->id;
		$sql = "insert into ".$user."_playlists (name,id_track) values ('$name','$id_track')";
		$qr = mysql_query($sql) or die(mysql_error());
	 }
	echo "true";
 }
if($_POST["action"] == "get_tracks"){
	$data = "[";
	$album = $_POST["album"];
	$sql = "select distinct id,tracks,artist,filename,cover,genre from music where albums = '$album'";
	$qr = mysql_query($sql) or die(mysql_error());
	if(!$qr){
		echo "false";
	}
	else{
		while($row = mysql_fetch_assoc($qr)){
			//декодируем имя файла
			$f = $row['filename'];
			$URL = $f;
			$URL = iconv("utf8","windows-1251",$URL);
			$URL = urlencode($URL);
			$URL = preg_replace("/[+]/","%20",$URL);
			$URL = preg_replace("/%2F/","/",$URL);

			$cover = $row["cover"];
			$cover = iconv("utf8","windows-1251",$cover);
			$cover = urlencode($cover);
			$cover = preg_replace("/[+]/","%20",$cover);
			$cover = preg_replace("/%2F/","/",$cover);

			$data .= '{"Title":"'.$row["tracks"].'","Artist":"'.$row["artist"].'","Filename":"'.$URL.'","Cover":"'.$cover.'","id_track":"'.$row["id"].'","Genre":"'.$row["genre"].'"},';
		}
		$data = substr($data, 0, strlen($data)-1);
		$data .= "]";
		// $data .= '@';
		// $sql = "select filename from music where albums = '$album'";
		// $qr = mysql_query($sql) or die(mysql_error());
		// while($row = mysql_fetch_assoc($qr)){
			// $data .= $row["filename"].",";ffggf
		// }
		echo "$data";
	}
 }
	

?>