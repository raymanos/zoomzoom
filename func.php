<?

function percentage($arr_numbers){
	$summ = 0;
	// Считаем сумму тэгов
	foreach ($arr_numbers["numbers"] as $key => $value) {
		$summ+=(int)$value;
	}
	// echo "Summ: $summ<br>";
	// Проходимся по массиву и считаем проценты
	foreach ($arr_numbers["numbers"] as $key => $value) {
		$arr_numbers["percents"][$key] = round( ((int)$value/$summ)*100 );
	}
	// var_dump($arr_percent_number);
	return $arr_numbers;
 }
// Statistic
function getTagsCount($login, $arr_tags){
	// Return array $cnt_arr_tags >>>
	// Все существующие тэги
	// $arr_tags = array('Боевик','Детектив','Драма','История','Комедия',
	// 			 'Мелодрама','Приключения','Спорт','Триллер','Ужасы',
	// 			 'Фантастика','Фэнтези','Русский');
	// return this array with numbers
	$cnt_arr_tags = array("numbers" => array(
										'Боевик'     => 0,
					 					'Детектив'   => 0,
					 					'Драма'      => 0,
					 					'История'    => 0,
					 					'Комедия'    => 0,
					 					'Мелодрама'  => 0,
					 					'Приключения'=> 0,
					 					'Спорт'      => 0,
					 					'Триллер'    => 0,
					 					'Ужасы'      => 0,
					 					'Фантастика' => 0,
					 					'Фэнтези'    => 0,
					 					'Русский'    => 0),
						   "percents"=> array(
						   				'Боевик'    => 0,
						   				'Детектив'  => 0,
						   				'Драма'     => 0,
						   				'История'   => 0,
						   				'Комедия'   => 0,
						   				'Мелодрама' => 0,
						   				'Приключения'=>0,
						   				'Спорт'     => 0,
						   				'Триллер'   => 0,
						   				'Ужасы'     => 0,
						   				'Фантастика'=> 0,
						   				'Фэнтези'   => 0,
						   				'Русский'   => 0));
	// Берем все записи о сериалах пользователя
	$sql = "select id,NameEn,tags from $login";
	$res = mysql_query($sql) or die(mysql_error());
	$data = array();
	$j = 0;
	while($row = mysql_fetch_assoc($res)){
		$data[$j]['tags'] = $row['tags'];
		$j++;
	}
	// print_r($data);
	// Количество тегов (всего)
	$cnt_tags = count($arr_tags);
	// Количество записей у юзера в БД
	$cnt_users_tags = count($data);
	// echo "$cnt_users_tags";
	for($i=0; $i < $cnt_tags; $i++){
		// Получае текущий тэг
		$current_tag = $arr_tags[$i];

		for($j=0; $j <= $cnt_users_tags; $j++){
			// Превращаем в массив из строки "Комедия,Ужасы,Триллер"
			$user_tags = explode(',', (string)($data[$j]['tags']));

			if( in_array($current_tag, $user_tags) ) 
				$cnt_arr_tags["numbers"][$current_tag]++;
		}
			// echo "Cur.tag: $current_tag<br>";
			// // print_r($user_tags);

			// echo "<br>";
			// echo "-----------------------------------------------------";
			// print_r($cnt_arr_tags);
			// echo "-----------------------------------------------------";
			// if($i == 2) exit;
		}
	// Удалить пустые ячейки массива
	foreach ($cnt_arr_tags["numbers"] as $key => $value) {
		if($key == '')
			unset($cnt_arr_tags[$key]);
	}
	// var_dump($cnt_arr_tags);
	return $cnt_arr_tags;
 }
function PaintTagsCount($arr_numbers, $arr_tags){
	/* pChart library inclusions */ 
	include("lib/pChart2.1.3/class/pData.class.php"); 
	include("lib/pChart2.1.3/class/pDraw.class.php"); 
	include("lib/pChart2.1.3/class/pPie.class.php"); 
	include("lib/pChart2.1.3/class/pImage.class.php");
	// Сортируем в порядке убывания
	arsort($arr_numbers["numbers"]);
	$array_numbers = array();
	$array_legend = array();
	foreach ($arr_numbers["numbers"] as $key => $value) {
		$array_numbers[] = $value;
		$array_legend[] = $key;
	}
	///////////////////////////////////////////////////////
	/* Формируем данные */ 
	$MyData = new pData();    
	$MyData->addPoints($array_numbers,"ScoreA");   
	$MyData->setSerieDescription("ScoreA","Application A"); 
	$MyData->addPoints($array_legend,"Labels"); 
	$MyData->setAbscissa("Labels"); 
	/* Формируем картинку */
	$myPicture = new pImage(550,400,$MyData); 
	 /* Draw background */ 
 	$Settings = array("R"=>173, "G"=>152, "B"=>217, "Dash"=>1, "DashR"=>193, "DashG"=>172, "DashB"=>237); 
 	// $myPicture->drawFilledRectangle(0,0,700,400,$Settings);  
 	$Settings = array("StartR"=>255, "StartG"=>255, "StartB"=>255, "EndR"=>255, "EndG"=>255, "EndB"=>255, "Alpha"=>50); 
 	// $myPicture->drawGradientArea(0,0,700,270,DIRECTION_VERTICAL,$Settings); 
 	// $myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>62,"StartG"=>104,"StartB"=>142,"EndR"=>62,"EndG"=>104,"EndB"=>142,"Alpha"=>100)); 
	 /* Add a border to the picture */ 
 	// $myPicture->drawRectangle(0,0,549,399,array("R"=>0,"G"=>0,"B"=>0)); 
 	 /* Write the picture title */  
 	$myPicture->setFontProperties(array("FontName"=>"lib/pChart2.1.3/fonts/calibri.ttf","FontSize"=>10,"R"=>0,"G"=>0,"B"=>0)); 
 	$myPicture->drawText(210,13,"Статистика по жанрам",array("R"=>255,"G"=>255,"B"=>255)); 
 	 /* Set the default font properties */  
 	$myPicture->setFontProperties(array("FontName"=>"lib/pChart2.1.3/fonts/calibri.ttf","FontSize"=>10,"R"=>0,"G"=>0,"B"=>0)); 

 	 /* Enable shadow computing */  
 	$myPicture->setShadow(TRUE,array("X"=>2,"Y"=>2,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>50)); 
	/* Draw */
	$PieChart = new pPie($myPicture,$MyData); 
	$PieChart->draw2DPie(270,200,array("WriteValues"=>PIE_VALUE_PERCENTAGE,
									   "Radius"=>150,
									   "ValuePosition"=>PIE_VALUE_INSIDE,
									   "ValuePadding"=>-100,
									   "ValueR"=>0,"ValueG"=>0,"ValueB"=>0,"ValueAlpha"=>100,
									   "DrawLabels"=>TRUE,
									   "DataGapAngle"=>5,
									   "DataGapRadius"=>6,
									   "LabelStacked"=>TRUE,
									   "Border"=>TRUE)); 
	// $PieChart->draw2DPie(560,125,array("ValuePosition"=>PIE_VALUE_OUTSIDE,"ValuePadding"=>20,"ValueR"=>0,"ValueG"=>0,"ValueB"=>0,"ValueAlpha"=>100,"WriteValues"=>PIE_VALUE_PERCENTAGE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE,"BorderR"=>255,"BorderG"=>255,"BorderB"=>255));
	$myPicture->Render("chart1.png"); 
 }
// Accounts
function deleteAccount($login){
	//delete user data
	$sql = "drop table $login";
	mysql_query($sql) or die(mysql_error());
	//delete user from users table
	$sql = "delete from users where login='$login'";
	mysql_query($sql) or die(mysql_error());
	$sql = "delete from friends where login='$login'";
	mysql_query($sql) or die(mysql_error());

	session_start();
	unset($_SESSION['login']);
	unset($_SESSION['password']);
	session_destroy();
	setcookie('pid','',time()-400);
	// header('location: index.php');
	return true;
 }
function getDataFromLogin($login, $friend_status){
	$sql = "select name,last_name,avatar from users where login='$login'";
	$res = mysql_query($sql) or die(mysql_error());
	$data = mysql_fetch_assoc($res);
	$first_name = $data['name'];
	$last_name = $data['last_name'];
	$ava = $data['avatar'];	

	echo "<div>
			<button class='btn' id='back_button'>Назад</button>";
	if($friend_status == 'true'){
		//friend
		echo "<button class='btn' id='add_friend'>Удалить из друзей</button>";
	}
	else{
		//not friend
		echo "<button  style='margin-left:10px;' class='btn' id='add_friend'>Добавить в друзья</button>";
	}


	echo "<img  style='margin-left:10px;' src=$ava class='ava'>";
	echo "<b  style='margin-left:10px;'>$login</b><br>";
	echo "</div>";
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

function getSt($status){
	switch ($status) {
		case 'all':
			return 3;
			break;
		case 'now':
			return 1;
			break;
		case 'will':
			return 2;
			break;
		case 'notlike':
			return 4;
			break;
	}
 }  
function user(){
	if (isset($_SESSION['login'])){//$_COOKIE
		return true;
	}
	else{
		return false;
	}
 }
function YesNo($var){
 	if ($var) return 'Да';
 	else return 'Нет';
 } 
function GetStatus($status){

	switch ($status) {
		case 'all':
			return 'Полностью просмотрен';
			break;
		case 'now':
			return 'Смотрю';
			break;
		case 'will':
			return 'Буду смотреть';
			break;
		case 'notlike':
			return 'Смотрел, не понравилось';
			break;
		case 1:
			return 'Смотрю';
			break;
		case 2:
			return 'Буду смотреть';
			break;
		case 3:
			return 'Полностью просмотрен';
			break;
		case 4:
			return 'Смотрел, не понравилось';
			break;
		
		default:
			return '-';
			break;
	}
 }


function PaintTable2($Header, $fields, $content, $class, $status){
	echo "<h2>".$Header."</h2>";
	$cnt = count($fields);
	// echo "<table class='main_table tablesorter'><thead><tr class='firstLine'><th>#</th>";
 	echo "<table class='table table-condensed tablesorter' style='width:97%;margin-left:20px;'><thead><tr><th>#</th>";
 	for($i = 0; $i < $cnt; $i++){
 		echo '<th>'.$fields[$i].'</th>';
 	}
 	echo '</thead>';
 	$j = 0;
 	echo '<tbody>';
	while($row = mysql_fetch_assoc($content)){
		$j++;
		echo "<tr class='lineOne'><td width='10'>$j</td>";
		// echo "{$row['NameEn']{$row['Min']}";
		for ($i=0; $i < $cnt+1; $i++) { 

			// print_r($row);
			if($class[$i] == 'Min'){
				// echo "Min:".$class[$i];
			}
			if($class[$i] == 'NameEn'){
				echo "<td class=NameEn><a href=index.php?name_en='".rawurlencode($row[$class[$i]])."'>".$row[$class[$i]]."</a></td>";
			}
			if($class[$i] == 'NameRu'){
				echo "<td class=NameRu><a href=index.php?name_ru='".rawurlencode($row[$class[$i]])."'>".$row[$class[$i]]."</a></td>";
			}
			if($class[$i] == 'Sems'){
				echo '<td class=Sems>'.$row[$class[$i]].'</td>';
			}			
			if($class[$i] == 'Stars'){
				echo '<td class=Stars>'.$row[$class[$i]].'/10</td>';
			}
			if($class[$i] == 'Status'){
				// echo '<td class=Status>'.GetStatus($row[$class[$i]]).'</td>';
				// $status = $row[$class[$i]];
				// echo $row[$class[$i]];
			}
			if($class[$i] == 'Comment'){
				echo '<td class=Comment>'.$row[$class[$i]].'</td>';
			}
			if($class[$i] == 'link'){
				echo '<td class=link><a id="_link" target="_blank" href="'.$row[$class[$i]].'">Kinopoisk</a></td>';
			}
			if($class[$i] == 'Tags'){
				$tags_str = $row[$class[$i]];
				$tags_arr = explode(',', $tags_str);
				// print_r($tags_arr);
				$count = count($tags_arr);
				echo "<td class='tag_field'>";
				for ($k=0; $k < $count; $k++){
					echo "<a class='tag_link text-info' href='index.php?tag=".$tags_arr[$k]."'>".$tags_arr[$k]."</a> ";
					// echo '!';
				}
				echo "</td>";
				// echo '<td class=tag_field>'.$tags_arr[0].$tags_arr[1].$tags_arr[2].'</td>';
				// echo '<td class=tag_field>'."$cnt fdvdfdffdgdfdfgdfgfd".'</td>';
			}
			if($class[$i] == 'id'){
				$id = $row['id'];
 				echo "<td class='action'>
 				<a style='display:none;' id='_status' href='$status'></a>
 				<a id='delete$id' class='delete' href='#$id'><i class='icon-remove icon-2x'></i>&nbsp;&nbsp;</a>
 				<a id='$id' class='update' href='#inline'><i class='icon-pencil'></i></a>
 				</td>";

			}
			// echo '<td class='.$class[$i].'>'.$row[$class[$i]].'</td>';
		}
		echo '</tr>';
		
	}
	echo '</tbody>';
	echo '</table>';

 }

function footer(){
	echo 
	"<footer>
		<div class='container'>
			<div class='row'>
				<div class='span4'>
					<h4>Контакты</h4>
						<ul class='icons'>
							<li>Email:<a href='mailto:raymanos00@gmail.com'>Макс Курков</a></li>
							<li>VK:<a href='http://vk.com/raymanos'>vk.com/raymanos</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</footer>";
 }

function clearData($data){
	return mysql_real_escape_string(trim(strip_tags($data)));
 

 }

?>