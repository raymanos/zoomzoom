<?
function Log_($message){
	error_log("[".date("Y-m-d H:i:s")."]: ".$message."\n", 3, "/var/www/player/log.txt");
 }
function getGenre($genre_num){
	$allGenres = ['Blues','Classic Rock','Country','Dance','Disco','Funk','Grunge',  
    'Hip-Hop','Jazz','Metal','New Age','Oldies','Other','Pop','R&B',  
    'Rap','Reggae','Rock','Techno','Industrial','Alternative','Ska',  
    'Death Metal','Pranks','Soundtrack','Euro-Techno','Ambient',  
    'Trip-Hop','Vocal','Jazz+Funk','Fusion','Trance','Classical',  
    'Instrumental','Acid','House','Game','Sound Clip','Gospel','Noise',  
    'Alternative Rock','Bass','Punk','Space','Meditative','Instrumental Pop',  
    'Instrumental Rock','Ethnic','Gothic','Darkwave','Techno-Industrial','Electronic',  
    'Pop-Folk','Eurodance','Dream','Southern Rock','Comedy','Cult','Gangsta',  
    'Top 40','Christian Rap','Pop/Funk','Jungle','Native US','Cabaret','New Wave',  
    'Psychadelic','Rave','Showtunes','Trailer','Lo-Fi','Tribal','Acid Punk',  
    'Acid Jazz','Polka','Retro','Musical','Rock & Roll','Hard Rock','Folk',  
    'Folk-Rock','National Folk','Swing','Fast Fusion','Bebob','Latin','Revival',  
    'Celtic','Bluegrass','Avantgarde','Gothic Rock','Progressive Rock',  
    'Psychedelic Rock','Symphonic Rock','Slow Rock','Big Band','Chorus',  
    'Easy Listening','Acoustic','Humour','Speech','Chanson','Opera',  
    'Chamber Music','Sonata','Symphony','Booty Bass','Primus','Porn Groove',  
    'Satire','Slow Jam','Club','Tango','Samba','Folklore','Ballad',  
    'Power Ballad','Rhytmic Soul','Freestyle','Duet','Punk Rock','Drum Solo',  
    'Acapella','Euro-House','Dance Hall','Goa','Drum & Bass','Club-House',  
    'Hardcore','Terror','Indie','BritPop','Negerpunk','Polsk Punk','Beat',  
    'Christian Gangsta','Heavy Metal','Black Metal','Crossover','Contemporary C',  
    'Christian Rock','Merengue','Salsa','Thrash Metal','Anime','JPop','SynthPop'];
    if( $genre_num > count($allGenres) )
    	return 'Other';
    else
    	return $allGenres[$genre_num];
 }
function mp3tags($filename){
	$f = fopen($filename, 'rb');
	rewind($f);
	fseek($f, -128, SEEK_END);
	$tmp = fread($f,128);
	if ($tmp[125] == Chr(0) and $tmp[126] != Chr(0)) 
	{
		// ID3 v1.1
		$format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a28COMMENT/x1/C1TRACK/C1GENRENO';
	} 
	else 
	{
		// ID3 v1
		$format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a30COMMENT/C1GENRENO';
	} 
	return unpack($format, $tmp);
 } 
function addToTable($_artist, $_album, $_title, $_genre, $_year, $_filename, $_cover, $_date){
	$sql = "insert into `music` (artist,albums,tracks,filename,cover,genre,year,date) values 
			('$_artist','$_album','$_title','$_filename','$_cover','$_genre','$_year','$_date')";
	$q = mysql_query($sql) or die(mysql_error());
 }
function setInfoToTable($_ctracks, $_stracks){
	$sql = "insert into `music_information` (count_tracks,size_tracks,lastScan) values 
			('$_ctracks','$_stracks', now())";
	$q = mysql_query($sql) or die(mysql_error());
 }
function getInfoTable(){
	$q = mysql_query("select count_tracks,size_tracks from music_information") or die(mysql_error());
	$row = mysql_fetch_assoc($q);
	$data["count"] = $row["count_tracks"];
	$data["size"] = $row["size_tracks"];
	return $data;
 }
function addFolder($folder){
	$dir_iterator = new RecursiveDirectoryIterator($folder);
	$iterator     = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
	Log_("Запускаю сканирование по каталогу $folder");
	$count_tracks = $size_tracks = 0;
	$current_date = date("Y-m-d H:i:s"); 
	$cc = 0;
	foreach ($iterator as $file) {
		if( $file != '.' && $file != '..')
			if( $file->isFile() )
			{
				if( $file->getExtension() == 'mp3' )
				{
					$tags = mp3tags($file);
	
					$filename = $file;
					$cover = dirname($filename)."/cover.jpg";
					if (mb_detect_encoding($filename, 'UTF-8', true) === false) { 
							
	    					// $filename = iconv("windows-1251","UTF-8",$filename); 
						echo "Coding into WINDOWS-1251<br>";
						$filename = iconv("WINDOWS-1251","UTF-8",$filename); 
						$cover = dirname($filename)."/cover.jpg";
	    			}
	
					$artist   = iconv("windows-1251","UTF-8",$tags["ARTISTS"]);
					$album    = iconv("windows-1251","UTF-8",$tags["ALBUM"]);
					$title    = iconv("windows-1251","UTF-8",$tags["NAME"]);
					$genre    = getGenre($tags["GENRENO"]);
					$year     = $tags["YEAR"];
	
					$size_tracks  += $file->getSize();
					$count_tracks ++; 
					$filename = mysql_real_escape_string($filename);
					$artist   = mysql_real_escape_string($artist);
					$album    = mysql_real_escape_string($album);
					$title    = mysql_real_escape_string($title);
					$cover    = mysql_real_escape_string($cover);
	
					addToTable($artist, $album, $title, $genre, $year, $filename, $cover, $current_date);
					Log_("Добавление файла: $filename");
					Log_("С тегами: $artist - $album - $title - $genre - $year");
					// echo "$artist|$album|$title|$genre|$year<br> $filename";
					// echo "<hr><br>";
			    }
			}
	}
	Log_("Сканирование завершено. Добавлено файлов $count_tracks.");
	$size_tracks_mb = round($size_tracks / 1000000000,2);
	$mas = getInfoTable();
	$size_tracks_mb += $mas["size"];
	$count_tracks   += $mas["count"];
	setInfoToTable($count_tracks, $size_tracks_mb);
	return 1;
}


function getSerialsCount(){
	$sql = "select count(NameEn) from serial_db";
	$res = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_row($res);
	$count = $data[0];
	return $count;
 }
function getSerialByName($name, $name_ru=false){
	$sql = "select login from users";
	$res = mysql_query($sql) or die(mysql_error());
	// $users = array('raymanos');
	$serials = array();
	while($data = mysql_fetch_assoc($res)){
		$users[] = $data['login'];
	}

	$fields = "NameEn,NameRu,Stars,Comment,link,Tags";
	$count_users = count($users);
	$sql = '';
	for($i=0; $i < $count_users; $i++){
		if($count_users-1 == $i){
			if(!$name_ru)
				$sql .= "select $fields from {$users[$i]} where NameEn = $name ";
			else
				$sql .= "select $fields from {$users[$i]} where NameRu = $name ";
		}
		else{
			if(!$name_ru)
				$sql .= "select $fields from {$users[$i]} where NameEn = $name union ";
			else
				$sql .= "select $fields from {$users[$i]} where NameRu = $name union ";
		}
	}

	$res = mysql_query($sql) or die(mysql_error());
	$fields = array('Название(En)','Название(Ru)','Оценка','Комментарий','Ссылка','Теги');
	$class = array('NameEn','NameRu','Stars','Comment','link','Tags','Min');

	return array('Header'=> "Запрос по имени '$name'",
				 'Fields'=> $fields,
				 'Res'   => $res,
				 'Class' => $class,
				 'Status'=> $st);
 }
function getSettings($id_user)
{
	$res = mysql_query("select volume from settings where id_user = '$id_user'") or die(mysql_error());
	$volume = mysql_fetch_assoc($res);
	return $volume["volume"];
}
function setSettings($id_user,$volume)
{
	// Сначала ищем настройку
	$res = mysql_query("select volume from settings where id_user = '$id_user'") or die(mysql_error());
	$count = mysql_num_rows($res);

	// print_r($res);
	if($count>0)
	{
		// Есть уже настройка
		// Тогда обновляем
		$res = mysql_query("update settings set volume = '$volume' where id_user = '$id_user'") or die(mysql_error());
		// $count = mysql_fetch_array($res);
		if($res)
			echo "true";
		else
			echo "false";
	}
	else
	{
		$res = mysql_query("insert into settings (id_user,volume) values ('$id_user','$volume') ");
		if($res)
			echo "true";
		else
			echo "false";
	}


} 
 // Возвращает количество прослушиваний всего и по пользователю
function getTrackCountByUser($id_track,$id_user)
{
	$sql = "select id_user,count,last_date from counts where id_track = '$id_track'";
	$res = mysql_query($sql) or die(mysql_error());
	$data = array();
	while($row = mysql_fetch_assoc($res))
	{
		$data["login"]     = $row["login"];
		$data["id_user"]   = $row["id_user"];
		$data["count"]     = $row["count"];
		$data["last_date"] = $row["last_date"]; 
	}
	return json_encode($data);
}
//return raymanos,ivan,...,badsanta
function getAllUsers(){
	$sql = "select login from users";
	$res = mysql_query($sql) or die(mysql_error());
	$users = '';
	while($row = mysql_fetch_assoc($res)){
		$users .= $row['login'].',';
	}
	//убрать последнюю запятую
	$users = substr($users, 0, -1);
	return $users;
 }
function getUsersCount(){
	$sql = "select count(login) from users";
	$res = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_row($res);
	$count = $data[0];
	return $count;
 }
function delUserFriend($login, $user_login){
	//$login - твой логин, $user_login - логин которого мы добавляем
	if((!empty($login)) and !empty($user_login)){
		//получаем массив с id друзей
		$sql = "select friends_id from friends where login='$login'";
		$res = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_assoc($res) or die(mysql_error());	
		$data = $data['friends_id'];
		$friends_id = explode(',',$data);
		//получаем id user_login(которого добавляем в друзья)
		$sql = "select id from users where login='$user_login'";
		$res = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_assoc($res);
		$user_id = $data['id'];
		//если такого логина нету
		if(!empty($user_id)){
			//проверяем, есть ли массиве искомый id, а то удалять будет нечего
			if(in_array($user_id, $friends_id)){
				//удаляем скомый id
				// print_r($friends_id);
				$key = array_search($user_id, $friends_id);
				if ($key !== false){
    				unset($friends_id[$key]);
    				//обратно в строку
    				// print_r($friends_id);
					$friends_id_str = implode(',', $friends_id);
					//обратно в базу данных
					$sql = "update friends set friends_id='$friends_id_str' where login='$login'";
					mysql_query($sql) or die(mysql_error());
					return true;
				}
				else{
					return false;
				}

			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
 }
function addUserFriend($login, $user_login){
	//$login - твой логин, $user_login - логин которого мы добавляем
	if((!empty($login)) and !empty($user_login)){
		//получаем массив с id друзей
		$sql = "select friends_id from friends where login='$login'";
		$res = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_assoc($res) or die(mysql_error());	
		$data = $data['friends_id'];
		$friends_id = explode(',',$data);
		//получаем id user_login(которого добавляем в друзья)
		$sql = "select id from users where login='$user_login'";
		$res = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_assoc($res);
		$user_id = $data['id'];
		//если такого логина нету
		if(!empty($user_id)){
			//добавляем в массив id искомый user_id
			if(!in_array($user_id, $friends_id)){
				array_push($friends_id,$user_id);
				//обратно в строку
				$friends_id_str = implode(',', $friends_id);
				//обратно в базу данных
				$sql = "update friends set friends_id='$friends_id_str' where login='$login'";
				mysql_query($sql) or die(mysql_error());
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return false;
		}
	}
	else{
		return false;
	}
 }
function isUserFriend($login, $user_login){
	// true - friend, false - not friend
	//$login - твой логин, $user_login - логин которого мы добавляем
	if((!empty($login)) and !empty($user_login)){
		//получаем массив с id друзей
		$sql = "select friends_id from friends where login='$login'";
		$res = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_assoc($res) or die(mysql_error());	
		$data = $data['friends_id'];
		$friends_id = explode(',', $data);
		//получаем id user_login(которого добавляем в друзья)
		$sql = "select id from users where login='$user_login'";
		$res = mysql_query($sql) or die(mysql_error());
		$data = mysql_fetch_assoc($res);
		$user_id = $data['id'];
		//если такого логина нету
		if(!empty($user_id)){
			//проверяем есть ли он в массиве
			if(in_array($user_id, $friends_id))
				return true;
			else
				return false;
		}
		else
			return false;	
	}
	else
		return false;
	
 }
function getFriendsList($login){
	//return a list friends, like this '69','68'
	$sql = "select friends_id from friends where login='$login'";
	$res = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_assoc($res);
	$friends_id = $data['friends_id'];
	if($friends_id{0} == ',')
		$friends_id = substr($friends_id, 1);
	return $friends_id;
	// if(!empty($friends_id))
	// 	return implode(',', explode(',', $friends_id));
	// // implode(glue, pieces)
	// else
	// 	return false;
 }
function getUsers($login, $friends = false, $friends_id = ""){
	echo "<br>";
	echo "<button class='btn' id='back_button'>Назад</button>";
	if(!$friends){
		$sql = "select login,name,last_name,avatar from users where login <>'$login' and private <> 1";
		// echo "1$sql";
	}
	else{
		if(!empty($friends_id)){
			$sql = "select login,name,last_name,avatar from users where id IN ($friends_id)";
			// echo "2$sql";
		}
		else{
			echo "У вас пока нет ни одного друга :(";
				exit;
		}
	}

	echo "<div id='users_list'><table class='table table-condensed'><thead><tr> <th> </th><th> </th><th> </th></thead><tbody>";
	$res = mysql_query($sql) or die(mysql_error());
	$j = 0;
	while($row = mysql_fetch_assoc($res)){
		$login = $row['login'];
		$first_name = $row['name'];
		$last_name = $row['last_name'];
		$ava = $row['avatar'];
		// echo "<div id='users_list'><li>";
		// echo "<img src='$ava' class='users_ava'><a href='?login=$login'>$login</a>";
		// echo "</li></div>";
		$j++;
		echo "<tr><td width='10'>$j</td>";
		echo "<td><img src='$ava' class='ava-2x'></td>";
		echo "<td><a class='users_link' href='?login=$login'>$login</a></td>";
	}
		echo "</table></div>";
 }


function user(){
	if (isset($_SESSION['login'])){//$_COOKIE
		return true;
	}
	else{
		return false;
	}
 }
function getIDUser($login)
{
	$sql = "select id from users where login = '$login'";
	$qr = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_assoc($qr);
	return $row["id"];

}




function clearData($data){
	return mysql_real_escape_string(trim(strip_tags($data)));
 }

?>