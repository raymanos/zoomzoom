<?php 
	mysql_connect('localhost','root','1');
	mysql_select_db('musicworld') or die(mysql_error());
	mysql_query("SET NAMES 'utf8");
	mysql_query("SET CHARACTER SET 'utf8'");
?>