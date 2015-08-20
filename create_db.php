<?php
header('Content-Type: text/html;charset=utf-8');
define("MUSIC_DIR", "/media/Eva/Music");
// define("MUSIC_DIR", "/var/www/music");
// define("MUSIC_TABLE", "music");
define("MUSIC_TABLE", "music_");
// define("DIR_CUT_COUNT",8);
define("DIR_CUT_COUNT",8);

include "connect_db.php";
		ini_set('error_reporting', E_ALL);
		ini_set('display_errors', 1);	
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
	if ($tmp[125] == Chr(0) and $tmp[126] != Chr(0)) {
	// ID3 v1.1
	$format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a28COMMENT/x1/C1TRACK/C1GENRENO';
	} 
	else {
	// ID3 v1
	$format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a30COMMENT/C1GENRENO';
	} 
	return unpack($format, $tmp);
 } 

function myscan(&$arr, $dir){ 
	$cont=glob($dir."/*"); 
	// echo "myscan";
	foreach($cont as $file){ 
		if (is_dir($file)){ 
			myscan($arr, $file); 
		} 
		else{ 
			if (strpos($file, ".mp3")!==false){ 
				// echo "$filename<br>";

				$tags = mp3tags($file);
				print_r($tags);
				//$filename = $file;
				$artist = $tags["ARTISTS"];
				//$album = $tags["ALBUM"];
				//$tracks = $tags["NAME"];
				// $filename = iconv("windows-1251","UTF-8",$file);
				$filename = substr($file, DIR_CUT_COUNT);
				$filename = iconv("windows-1251","UTF-8",$filename);
				$artist = iconv("windows-1251","UTF-8",$tags["ARTISTS"]);
				// $artist = $tags["ARTISTS"];
				//$artist = iconv("windows-1251","UTF-8",$artist);
				$album = iconv("windows-1251","UTF-8",$tags["ALBUM"]);
				$tracks = iconv("windows-1251","UTF-8",$tags["NAME"]);
				// $genre = iconv("windows-1251","UTF-8",$tags["GENRENO"]);
				$genre = getGenre($tags["GENRENO"]);
				$year = $tags["YEAR"];

				$cover = substr(dirname(iconv("windows-1251","UTF-8",$file))."/cover.jpg",DIR_CUT_COUNT);

				$filename = mysql_real_escape_string($filename);
				$artist = mysql_real_escape_string($artist);
				$album = mysql_real_escape_string($album);
				$tracks = mysql_real_escape_string($tracks);
				$cover = mysql_real_escape_string($cover);
				//записываем в базу данных
				$sql = "insert into ".MUSIC_TABLE." (artist,albums,tracks,filename,cover,genre,year) values 
				('$artist','$album','$tracks','$filename','$cover','$genre','$year')";
				$q = mysql_query($sql) or die(mysql_error());
				echo "$filename<br>";
				echo "<hr><br>";
			} 
		} 
	}	 
 } 
 echo "dd";
//смотрим есть ли таблица, если нет создаем и сканируем
// $q = mysql_query("select id from music_") or die("1ddd".mysql_error());
// if(!$q){
	//создаем таблицу
	$query = mysql_query("create table if not exists `".MUSIC_TABLE."` (
							`id` int(11) not null auto_increment,
							`artist` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`albums` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`tracks` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`filename` varchar(300) COLLATE utf8_general_ci NOT NULL,
							`cover` varchar(300) COLLATE utf8_general_ci,
							`genre` varchar(30) COLLATE utf8_general_ci,
							`year` varchar(30) COLLATE utf8_general_ci,
							primary key(id))") or die(mysql_error());
	$query = mysql_query("create table if not exists `playlist` (
							`id` int(11) not null auto_increment,
							`name` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`user` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`artist` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`title` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`filename` varchar(300) COLLATE utf8_general_ci NOT NULL,
							`cover` varchar(300) COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());
	echo "Query: $query";	
	//потом сканируем
	
	$arr=array(); 
	// myscan($arr, "music"); 
	myscan($arr, MUSIC_DIR); 
	echo "END";
// }
// else 
// {
	// echo "net table";
// }

?>