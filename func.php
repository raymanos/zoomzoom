<?



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
function getSerialsByTag($tag){
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
		if($count_users-1 == $i)
			$sql .= "select $fields from {$users[$i]} where Tags like '%$tag%' COLLATE utf8_bin ";
		else
			$sql .= "select $fields from {$users[$i]} where Tags like '%$tag%' COLLATE utf8_bin union ";
	}

	$res = mysql_query($sql) or die(mysql_error());
	$fields = array('Название(En)','Название(Ru)','Оценка','Комментарий','Ссылка','Теги');
	$class = array('NameEn','NameRu','Stars','Comment','link','Tags','Min');

	return array('Header'=> "Запрос по тегу '$tag'",
				 'Fields'=> $fields,
				 'Res'   => $res,
				 'Class' => $class,
				 'Status'=> $st);
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