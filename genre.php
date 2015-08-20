<?php
include "connect_db.php";

//отрисовываем листбокс с артистами
$genres = array();
$qr = mysql_query("select distinct genre from music") or die(mysql_error());
while ($row = mysql_fetch_assoc($qr)){
	echo "<option>".$row['genre']."</option>";
}
?>