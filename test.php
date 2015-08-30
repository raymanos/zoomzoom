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
function addToTable($_artist, $_album, $_title, $_genre, $_year, $_filename, $_cover)
{
	$sql = "insert into `music` (artist,albums,tracks,filename,cover,genre,year) values 
			('$_artist','$_album','$_title','$_filename','$_cover','$_genre','$_year')";
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
							`id` int(11) not null auto_increment,
							`artist` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`albums` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`tracks` varchar(100) COLLATE utf8_general_ci NOT NULL,
							`filename` varchar(300) COLLATE utf8_general_ci NOT NULL,
							`cover` varchar(300) COLLATE utf8_general_ci,
							`genre` varchar(30) COLLATE utf8_general_ci,
							`year` varchar(30) COLLATE utf8_general_ci,
							primary key(id))") or die(mysql_error());
$query = mysql_query("create table if not exists `music_information` (
							`id` int(11) not null auto_increment,
							`size_tracks`  varchar(30)  COLLATE utf8_general_ci NOT NULL,
							`count_tracks` varchar(300) COLLATE utf8_general_ci NOT NULL,
							`lastScan` datetime,
							primary key(id))") or die(mysql_error());
$dir_iterator = new RecursiveDirectoryIterator("music");
$iterator     = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
// could use CHILD_FIRST if you so wish
$count_tracks = $size_tracks = 0;

foreach ($iterator as $file) {
	if( $file != '.' && $file != '..')
		if( $file->isFile() )
		{
			if( $file->getExtension() == 'mp3' )
			{
				$tags = mp3tags($file);

				$artist   = $tags["ARTISTS"];
				$filename = $file;
				$filename = iconv("windows-1251","UTF-8",$filename);
				$artist   = iconv("windows-1251","UTF-8",$tags["ARTISTS"]);
				$album    = iconv("windows-1251","UTF-8",$tags["ALBUM"]);
				$title    = iconv("windows-1251","UTF-8",$tags["NAME"]);
				$genre    = getGenre($tags["GENRENO"]);
				$year     = $tags["YEAR"];
				$cover = dirname(iconv("windows-1251","UTF-8",$file))."/cover.jpg";

				$size_tracks  += $file->getSize();
				$count_tracks ++; 
				$filename = mysql_real_escape_string($filename);
				$artist   = mysql_real_escape_string($artist);
				$album    = mysql_real_escape_string($album);
				$title    = mysql_real_escape_string($title);
				$cover    = mysql_real_escape_string($cover);

				echo "$artist|$album|$title|$genre|$year<br> $filename";
				echo "<hr><br>";

				// if($count_tracks == 5000) die();
		    	// echo "$file<br>";
		    }
		}

}
$size_tracks_mb = round($size_tracks / 1000000000,2);
setInfoToTable($count_tracks, $size_tracks_mb);
echo "size_tracks: $size_tracks_mb<br>count tracks: $count_tracks";
?>