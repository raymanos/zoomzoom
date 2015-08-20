<?php //pin 3011 megafon 
header('Content-Type: text/html;charset=utf-8');
include 'connect_db.php';
include 'func.php';

function showLoginForm(){
	$str = '<!DOCTYPE html>';	
	$str.= '<link rel="stylesheet" href="css/bootstrap.min.css" >';
	$str.= '<link rel="stylesheet" href="css/login.css" >';
	$str.= '<script src="js/bootstrap.min.js"></script>';
	$str.= "<script src='js/jquery.js'></script>";
	$str.= "<script src='js/login.js'></script>";

	$str.= "<div id='wrapper'>
				<form id='login_form' method='post' action='#' class='form-horizontal'>
          		<input type='text' name='login' id='login' placeholder='Логин'><br>
          		<input type='password' name='password' id='password' placeholder='Пароль'><br>
        		<label class='checkbox'>
         	 		<input type='checkbox' name='remember' checked> Запомнить меня
        		</label>
        		<button id='send_login' name='enter' type='submit' class='btn btn-primary'>Войти</button>
        					или <a class='text-info' href='registr.php'>Регистрация</a> 
    		</form></div>";
    	$str.= "<div id='error_login' class='alert alert-error'>Неправильная пара логин-пароль! Авторизоваться не удалось.</div>";
	
	return $str;
 }
function showRegForm(){
	$str = "Регистрация:";
	$str.= "<form method='post' action='reg.php'>";
	$str.= "<pre>";
	$str.= "Login:<input type='text' name='name'>";
	$str.= "Password:<input type='password' name='pass'>";
	$str.= "<input type='submit' name='registr' value='Зарегиться'>";
	$str.= "</pre>";
	$str.= "</form>";
	return $str;
 }
// function getUserData($login){
// 	$sql = "select avatar from users where login='$login'";
// 	$res = mysql_query($sql) or die(mysql_error());
// 	$da = mysql_fetch_array($res);
// 	$avatar = $da['avatar'];
//  	// $sql = "select NameEn,NameRu,Ended,Stars,Status,comment,link,id from $login";
//  	// $data = mysql_query($sql) or die(mysql_error());
//  	// $fields = array('Название(En)','Название(Ru)','Закончен',
//   //                 'Оценка','Статус','Комментарий','Ссылка','Действие');
//  	return array($avatar);
//  }
// function getUserContent_Search($login){
// 	$fields = array('Название(En)','Название(Ru)','Остановился на','Оценка','Комментарий','Ссылка','Действие');
// 	$class = array('NameEn','NameRu','Sems','Stars','Comment','link','id','Min');
// 	$sql = "select NameEn,NameRu,Sems,Stars,Comment,link,id,Min,Sec from $login where Groupa=1";
// 	$res = mysql_query($sql) or die(mysql_error());

// 	$sql = "select Status from $login where Groupa=1";
// 	$res_status = mysql_query($sql);
// 	$status = mysql_fetch_row($res_status);
// 	$st = GetStatus($status[0]);
// 	return array('Header'=> 'Поиск',
// 				 'Fields'=> $fields,
// 				 'Res'   => $res,
// 				 'Class' => $class,
// 				 'Status'=> $st);
//  }
// function getUserContent_Now($login){
// 	$fields = array('Название(En)','Название(Ru)','Остановился на','Оценка','Комментарий','Ссылка','Теги','Действие');
// 	$class = array('NameEn','NameRu','Sems','Stars','Comment','link','Tags','id','Min');
// 	$sql = "select NameEn,NameRu,Sems,Stars,Comment,link,Tags,id,Min,Sec from $login where Groupa=1";
// 	$res = mysql_query($sql) or die(mysql_error());

// 	$sql = "select Status from $login where Groupa=1";
// 	$res_status = mysql_query($sql);
// 	$status = mysql_fetch_row($res_status);
// 	$st = GetStatus($status[0]);
// 	return array('Header'=> 'Смотрю сейчас',
// 				 'Fields'=> $fields,
// 				 'Res'   => $res,
// 				 'Class' => $class,
// 				 'Status'=> $st);
//  }
// function getUserContent_All($login){
// 	$fields = array('Название(En)','Название(Ru)','Оценка','Комментарий','Ссылка','Теги','Действие');
// 	$class = array('NameEn','NameRu','Stars','Comment','link','Tags','id');
// 	$sql = "select NameEn,NameRu,Stars,Comment,link,Tags,id from $login where Groupa=3";
// 	$res = mysql_query($sql) or die(mysql_error());

// 	$sql = "select Status from $login where Groupa=3";
// 	$res_status = mysql_query($sql);
// 	$status = mysql_fetch_row($res_status);
// 	$st = GetStatus($status[0]);
// 	return array('Header'=> 'Отсмотренные сериалы',
// 				 'Fields'=> $fields,
// 				 'Res'   => $res,
// 				 'Class' => $class,
// 				 'Status'=> $st);
//  }
// function getUserContent_Notlike($login){
// 	$fields = array('Название(En)','Название(Ru)','Оценка','Комментарий','Ссылка','Теги','Действие');
// 	$class = array('NameEn','NameRu','Stars','Comment','link','Tags','id');
// 	$sql = "select NameEn,NameRu,Stars,Comment,link,Tags,id from $login where Groupa=4";
// 	// $fields = array('Название(En)','Название(Ru)','Остановился на','Оценка','Комментарий','Ссылка','Действие');
// 	$res = mysql_query($sql) or die(mysql_error());

// 	$sql = "select Status from $login where Groupa=4";
// 	$res_status = mysql_query($sql);
// 	$status = mysql_fetch_row($res_status);
// 	$st = GetStatus($status[0]);
// 	return array('Header'=> 'Не понравились',
// 				 'Fields'=> $fields,
// 				 'Res'   => $res,
// 				 'Class' => $class,
// 				 'Status'=> $st);
//  }
// function getUserContent_Will($login){
// 	$fields = array('Название(En)','Название(Ru)','Оценка','Комментарий','Ссылка','Теги','Действие');
// 	$class = array('NameEn','NameRu','Stars','Comment','link','Tags','id');
// 	$sql = "select NameEn,NameRu,Stars,Comment,link,Tags,id from $login where Groupa=2";
// 	// $fields = array('Название(En)','Название(Ru)','Остановился на','Оценка','Комментарий','Ссылка','Действие');
// 	$res = mysql_query($sql) or die(mysql_error());

// 	$sql = "select Status from $login where Groupa=2";
// 	$res_status = mysql_query($sql);
// 	$status = mysql_fetch_row($res_status);
// 	$st = GetStatus($status[0]);
// 	return array('Header'=> 'Буду смотреть',
// 				 'Fields'=> $fields,
// 				 'Res'   => $res,
// 				 'Class' => $class,
// 				 'Status'=> $st);
//  }

session_start();

if(isset($_COOKIE['pid'])){
	$pid_ = $_COOKIE['pid'];
	$sql = "select login,password,pid,salt from users where pid='$pid_'";
	$res = mysql_query($sql);
	// Если совпадают PID, то входим автоматом
	if(mysql_num_rows($res) > 0){
		$res = mysql_fetch_array($res);
		//Получаем из базы логин и пароль
		$login = $res['login'];
		$password = $res['password'];
		$salt = $res["salt"];
		//Создаем сессию
		// session_start();
		$_SESSION['login'] = $login;
		$_SESSION['password'] = $password;
		//--------------------------------
		//Генерим новый pid
		$pid = md5($login.$salt.uniqid());
		$sql = "update users set pid='$pid' where login='$login'";
		mysql_query($sql) or dir(mysql_error());
		//Переустановим куку
		setcookie('pid',$pid,time()+5000);
		//--------------------------------
		//Получаем данные юзера: 
		//
		// getUserData
		// $arr = getUserData($_SESSION['login']);
		// $ava = $arr[0];
		$login = $_SESSION['login'];
		// $data2 = getUserContent($login);
		// print_r($data2);
		include "player.php";
		// echo "CONTENT START!!!";
		// echo 'Получаем данные юзера';
		exit;
	}
	else{
		setcookie('pid','',time()-4);
	}
 }

if(isset($_SESSION['login'])) {
	// 
	
	$login = $_SESSION['login'];
	// echo "F$login";
	//получаем salt
	$qr = mysql_query("select salt from users where login = '$login'");
	$tmp_array = mysql_fetch_assoc($qr);
	$salt = $tmp_array["salt"];

	$pid = md5($login.$salt.uniqid());
	$sql = "update users set pid='$pid' where login='$login'";
	mysql_query($sql) or dir(mysql_error());
	// User Data
	// $data2= getUserContent2($login,1);
	// print_r($data['Data']);
	// echo count($data['Data']);
	// print_r($data['Data'][0]);
	// $count = count($data['Data'])-1;
	// for($i=0; $i <= $count; $i++){
	// 	foreach ($data['Data'][$i] as $key => $value) {
	// 		echo "$key => $value <br>";
	// 	}
	// 	echo '//////////////<<<<<<<<<<<<<br>';
	// }


	// $data2 = getUserContent2($login,1);
	// $head = $data2[0];
	// $field = $data2[1];
	// $data = $data2[2];
	// echo "CONTENT START";
	include "player.php";
 }
 else{
  echo showLoginForm();
 }

?>