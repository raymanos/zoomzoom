<? 
session_start();
unset($_SESSION['login']);
unset($_SESSION['password']);
session_destroy();
setcookie('pid','',time()-400);
// print_r($_SESSION);
// $_SESSION=array();

// unset($_SESSION[key]); etc..
header('location: index.php');
 ?>