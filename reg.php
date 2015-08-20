<?php
//Добавить поле hash и проверить login
//Русские символы в базе
//Подумать за pid и hash
include 'connect_db.php';
include 'func.php';
// print_r($_POST);

 if($_POST['action'] == 'login'){//if(isset($_POST['enter'])){
 	//Enter
 	if(!empty($_POST['login']) and (!empty($_POST['password']))) {

 		$login = clearData($_POST['login']); //без ClearData
 		$password  = clearData($_POST['password']);

 		$sql = "select login,password,pid,salt from users where login='$login'";
 		$res = mysql_query($sql) or die(mysql_error());

 		if(mysql_num_rows($res) > 0){
 			$data = mysql_fetch_array($res);
 			$password_bd = $data['password'];
 			$login_bd = $data['login'];
 			$salt = $data['salt'];
 			if(($login == $login_bd) and (md5(md5($password).$salt) == $password_bd) ){
 				session_start();
 				$_SESSION['login'] = $login;
 				$_SESSION['password'] = $password;
 				//При входе нужно в любом случае генерить новый pid
 				$pid = md5($login.$salt.uniqid());
 				$sql = "update users set pid='$pid' where login='$login'";
 				mysql_query($sql) or die(mysql_error());	
 				//Если стоит галочка запомнить меня, то кидаем куку			
 				if(isset($_POST['remember'])){	
 					setcookie('pid',$pid,time()+5000);
 				}
 				//пароль подходит все норм
 				echo 'true';
 				//header('Location: index.php');
 				//exit();
 			}
 			//пароль не подходит
 			else{
 				// echo 'tut2';
 				echo 'false';
 				// header('Location: index.php');
 			}
 		}
 		else{
 			// echo 'tut3';
 			// header('Location: index.php');
 			echo 'false';
 		}
 	}
 }
 if($_POST['action'] == 'registr'){// if(isset($_POST['registr'])){
 	// print_r($_POST);
 	// echo 'Регистрация';
 	if(!empty($_POST['login']) and !empty($_POST['password'])) {
 		$login = clearData($_POST['login']);
 		$password = clearData($_POST['password']);

 		//Узнаем, не было ли такого юзера уже
 		$count = mysql_query("select login from `users` where `login`='".$login."'");
 		// echo $count;
 		//Если 0, значит нету таких логинов
 		if(mysql_num_rows($count) == 0){
 			$salt = mt_rand(100, 999);
			$tm = time();
			$password = md5(md5($password).$salt);
			$pid = md5($login.$salt.uniqid());
			mysql_query("insert into users (login,password,salt,pid) values ('$login','$password','$salt','$pid')") or die(mysql_error());
			//Создание таблиц с контентом юзера
			$sql = 'CREATE TABLE IF NOT EXISTS '.$login.'_playlists (
						`id` int(11) not null auto_increment,
						`name` varchar(30),
						`id_track` varchar(30),
						PRIMARY KEY(`id`)
					);';
			mysql_query($sql) or die(mysql_error());
			$sql = "CREATE TABLE IF NOT EXISTS ".$login."_genres (
						`id` int(11) not null auto_increment,
						`genre` varchar(10),
						`genre_count` varchar(10),
						PRIMARY KEY(`id`)
					);";
			mysql_query($sql) or die(mysql_error());
			$sql = "CREATE TABLE IF NOT EXISTS ".$login."_history (
						`id` int(11) not null auto_increment,
						`music_id` varchar(10),
						`loved` varchar(10),
						`rating` varchar(10),
						`count` varchar(10),
						PRIMARY KEY(`id`)
					);";
			mysql_query($sql) or die(mysql_error());
			session_start();
 			$_SESSION['login'] = $login;
 			$_SESSION['pass'] = $password;
 			echo "true";
 			// header('Location: index.php');
 		}
 		else{
 			//Такой логин уже есть в базе!
 			echo "duplicate_login";
 			// header('Location: registr.php');
 		}
 		

 	}
 }

?>