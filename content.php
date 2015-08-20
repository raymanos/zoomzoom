<?php
include "connect_db.php";

//отрисовываем листбокс с артистами
$artists = array();
$qr = mysql_query("select distinct artist from music") or die(mysql_error());
while ($row = mysql_fetch_assoc($qr)){
	echo "<option>".$row['artist']."</option>";
}
?>