<?php
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
	if ($tmp[125] == Chr(0) and $tmp[126] != Chr(0)) 
	{
		// ID3 v1.1
		$format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a28COMMENT/x1/C1TRACK/C1GENRENO';
	} 
	else 
	{
		// ID3 v1
		$format = 'a3TAG/a30NAME/a30ARTISTS/a30ALBUM/a4YEAR/a30COMMENT/C1GENRENO';
	} 
	return unpack($format, $tmp);
} 
function addToTable($_artist, $_album, $_title, $_genre, $_year, $_filename, $_cover, $_date)
{
	$sql = "insert into `music` (artist,albums,tracks,filename,cover,genre,year,date) values 
			('$_artist','$_album','$_title','$_filename','$_cover','$_genre','$_year','$_date')";
	$q = mysql_query($sql) or die(mysql_error());

}
function setInfoToTable($_ctracks, $_stracks)
{
	$sql = "insert into `music_information` (count_tracks,size_tracks,lastScan) values 
			('$_ctracks','$_stracks', now())";
	$q = mysql_query($sql) or die(mysql_error());
}
//создаем таблицу
$query = mysql_query("create table if not exists `music` (
							`id`        int(11) not null auto_increment,
							`artist`    varchar(150) COLLATE utf8_general_ci NOT NULL,
							`albums`    varchar(150) COLLATE utf8_general_ci NOT NULL,
							`tracks`    varchar(150) COLLATE utf8_general_ci NOT NULL,
							`filename`  varchar(300) COLLATE utf8_general_ci NOT NULL,
							`cover`     varchar(300) COLLATE utf8_general_ci,
							`genre`     varchar(100)  COLLATE utf8_general_ci,
							`year`      varchar(100)  COLLATE utf8_general_ci,
							`date`      datetime     COLLATE utf8_general_ci,
							`avg_star`  float(11,2)  COLLATE utf8_general_ci,
							`avg_count` float(11,2)  COLLATE utf8_general_ci,
							`count`     int(11)      COLLATE utf8_general_ci,
							primary key(id))") or die(mysql_error());

$query = mysql_query("create table if not exists `music_information` (
							`id` int(11) not null auto_increment,
							`size_tracks`  varchar(30)  COLLATE utf8_general_ci NOT NULL,
							`count_tracks` varchar(300) COLLATE utf8_general_ci NOT NULL,
							`lastScan` datetime COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());

// Таблица "Количесво воспроизведений"
$query = mysql_query("create table if not exists `counts` (
							`id`         int(11) not null auto_increment,
							`id_user`    int(11)  COLLATE utf8_general_ci NOT NULL,
							`id_track`   int(11)  COLLATE utf8_general_ci NOT NULL,
							`count`      int(11)  COLLATE utf8_general_ci NOT NULL,
							`last_date`  datetime COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());
// Таблица "Рейтинг треков"
$query = mysql_query("create table if not exists `rating` (
							`id`         int(11) not null auto_increment,
							`id_user`    int(11)  COLLATE utf8_general_ci NOT NULL,
							`id_track`   int(11)  COLLATE utf8_general_ci NOT NULL,
							`star`      int(11)  COLLATE utf8_general_ci NOT NULL,
							`last_date`  datetime COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());

// Плейлисты
$query = mysql_query("create table if not exists `user_playlist` (
							`id`           int(11) not null auto_increment,
							`id_user`      varchar(30)  COLLATE utf8_general_ci NOT NULL,
							`name`         varchar(300) COLLATE utf8_general_ci NOT NULL,
							`date`         datetime     COLLATE utf8_general_ci NOT NULL,
							`social`       int(11)      COLLATE utf8_general_ci NOT NULL,
							`social_ratng` int(11)      COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());

$query = mysql_query("create table if not exists `playlists` (
							`id`       int(11) not null auto_increment,
							`id_pls`   int(11)      COLLATE utf8_general_ci NOT NULL,
							`name_pls` varchar(30)  COLLATE utf8_general_ci NOT NULL,
							`id_user`  int(11)      COLLATE utf8_general_ci NOT NULL,
							`id_track` int(11)      COLLATE utf8_general_ci NOT NULL,
							`date`     datetime     COLLATE utf8_general_ci NOT NULL,
							`social`   int(11)      COLLATE utf8_general_ci NOT NULL,
							`social_rating` int(11) COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());

$query = mysql_query("create table if not exists `users` (
							`id`        int(11) not null auto_increment,
							`login`     varchar(40)  COLLATE utf8_general_ci NOT NULL,
							`password`  varchar(50)  COLLATE utf8_general_ci NOT NULL,
							`salt`      varchar(50)  COLLATE utf8_general_ci NOT NULL,
							`date_reg`  datetime COLLATE utf8_general_ci NOT NULL,
							`date_last` datetime COLLATE utf8_general_ci NOT NULL,
							`admin`     int(11) COLLATE utf8_general_ci NOT NULL,
							`pid`       varchar(50)  COLLATE utf8_general_ci NOT NULL,
							primary key(id))") or die(mysql_error());
// таблица настроек
$query = mysql_query("create table if not exists `settings` (
							`id`        int(11) not null auto_increment,
							`id_user`   int(11)  COLLATE utf8_general_ci NOT NULL,
							`volume`    int(11)  COLLATE utf8_general_ci NOT NULL DEFAULT 8,
							primary key(id))") or die(mysql_error());
$dir_iterator = new RecursiveDirectoryIterator("music");
$iterator     = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
// could use CHILD_FIRST if you so wish
$count_tracks = $size_tracks = 0;
$current_date = date("Y-m-d H:i:s"); 
$cc = 0;
foreach ($iterator as $file) {
	if( $file != '.' && $file != '..')
		if( $file->isFile() )
		{
			if( $file->getExtension() == 'mp3' )
			{
				$tags = mp3tags($file);

				$filename = $file;
				$cover = dirname($filename)."/cover.jpg";
				if (mb_detect_encoding($filename, 'UTF-8', true) === false) { 
						
    					// $filename = iconv("windows-1251","UTF-8",$filename); 
					echo "Coding into WINDOWS-1251<br>";
					$filename = iconv("WINDOWS-1251","UTF-8",$filename); 
					$cover = dirname($filename)."/cover.jpg";
    			}

				$artist   = iconv("windows-1251","UTF-8",$tags["ARTISTS"]);
				$album    = iconv("windows-1251","UTF-8",$tags["ALBUM"]);
				$title    = iconv("windows-1251","UTF-8",$tags["NAME"]);
				$genre    = getGenre($tags["GENRENO"]);
				$year     = $tags["YEAR"];

				$size_tracks  += $file->getSize();
				$count_tracks ++; 
				$filename = mysql_real_escape_string($filename);
				$artist   = mysql_real_escape_string($artist);
				$album    = mysql_real_escape_string($album);
				$title    = mysql_real_escape_string($title);
				$cover    = mysql_real_escape_string($cover);

				addToTable($artist, $album, $title, $genre, $year, $filename, $cover, $current_date);
				echo "$artist|$album|$title|$genre|$year<br> $filename";
				echo "<hr><br>";

				// if($count_tracks == 100) die();
		    	// echo "$file<br>";
		    }
		}
}
$size_tracks_mb = round($size_tracks / 1000000000,2);
setInfoToTable($count_tracks, $size_tracks_mb);
echo "size_tracks: $size_tracks_mb<br>count tracks: $count_tracks";
die("The END");
?>