<?php
$artist = urlencode($_GET['artist']);
$song = urlencode($_GET['song']);

/* We send the request to the site api.chartlyrics.com
   an it responds with an XML file */
$url = "http://api.chartlyrics.com/apiv1.asmx/SearchLyricDirect?artist=".$artist."&song=".$song."\"";
echo file_get_contents($url);
?>