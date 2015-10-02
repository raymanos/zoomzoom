//Настройки сервера
var query = "query.php";

function sendRequest(data, func){
	$.ajax({
		type:"POST",
		url:query,
		data:data,
		success:func(data) 
	});
 }
//Класс для работы с Audio HTML 5
function myJSplayer(audio_id){
	this.init = function(){
		console.log('init');
		//Инициализируем переменные
		this.playlist = new Array();//<--- this.track << this.count
		this.count = 0;
		this.track = 0;
		//-------------------------
		this.audioPlayer = $(audio_id)[0];
		this.audioPlayer.ontimeupdate = this.ontimeupdate;
		this.playerState = "pause";
		this.currentVolume = 0;
		this.volume = 0;
		//--------------------------
		// this.currentIDTrack  = 0;
		// this.currentNum      = 0;
		this.id_track      = "";
		this.artist   = "";
		this.album    = "";
		this.title    = "";
		this.genre    = "";
		this.cover    = "";
		this.year     = ""
		this.filename = "";
		this.NamePlaylist = "";
	 }
	this.pause = function(){
		this.audioPlayer.pause();
	 }
	this.play = function(){
		this.audioPlayer.play();
	 }
	this.setVolume = function(volume){
		this.audioPlayer.volume = volume;
	 }
	this.setcurrentTime = function(time){
		this.audioPlayer.currentTime = time;
	 }
	this.getDuration = function(){
		return this.audioPlayer.duration;
	 }
	this.getCurrentTime = function(){
		return this.audioPlayer.currentTime;
	 }
	this.ontimeupdate = function(){
		// console.log("update");
	 }
	// function dataAudio_callback(data){
	// 	this.id_track = data.id_track;
	// 	this.artist = data.artist;
	// 	this.album = data.album;
	// 	this.title = data.title;
	// 	this.year = data.year;
	// 	this.genre = data.genre;
	// 	this.cover = data.cover;
	// 	this.filename = data.filename;
	// }
	this.dataAudio = function(id_track,callback){
		var data = "&id_track="+id_track+"&action=dataAudio&";
		$.ajax({
			type:"POST",
			url:query,
			data:data,
			// dataType:"json",
			success: function(data){
				callback(data);
			}

	 	});
	 }
	this.playPause = function(){
		switch(this.playerState){
			case "pause":
				this.audioPlayer.pause();
				this.playerState = "play";
				// $("#player-play").attr("src","img/play2.png");
				console.log("vetka pause");
				break;
			case "play":
				this.audioPlayer.play();
				this.playerState = "pause";
				// $("#player-play").attr("src","img/pause.png");
				console.log("vetka play");
				break;
		}
 	 }
 	this.playTrack = function(){
		this.playerState = "pause";
		// $("#player-play").attr("src","img/pause.png");
		this.audioPlayer.src = this.playlist[this.track].filename;//this.playlist[num].filename;//filename;//this.playlist[num].filename; 
		this.audioPlayer.play();
		//формируем slider
		dur = this.audioPlayer.duration;
 	 }
 	this.updateNumbers = function(){
		var sec = new Number();
		var min = new Number();
		var final_string = "";
		var duration = Math.floor(this.audioPlayer.duration);
		var currentTime = Math.floor(this.audioPlayer.currentTime); 
		sec = Math.floor( currentTime );    
		min = Math.floor( sec / 60 );
		min = min >= 10 ? min : '0' + min;    
		sec = Math.floor( sec % 60 );
		sec = sec >= 10 ? sec : '0' + sec;
		final_string = min+":"+sec;

		sec = Math.floor( duration );    
		min = Math.floor( sec / 60 );
		min = min >= 10 ? min : '0' + min;    
		sec = Math.floor( sec % 60 );
		sec = sec >= 10 ? sec : '0' + sec;
		return final_string +=" / "+min+":"+sec;
 	 }
 	this.prevTrack = function(){
		if(this.track != 0){
			this.track--;
		}
		else
			this.track = 0;
		this.id_track        = this.playlist[this.track].id_track;
		this.artist          = this.playlist[this.track].artist;
		this.album           = this.playlist[this.track].album;
		this.title           = this.playlist[this.track].title;
		this.filename        = this.playlist[this.track].filename;
		this.cover           = this.playlist[this.track].cover;
		this.year            = this.playlist[this.track].year;
		this.genre           = this.playlist[this.track].genre;
		this.playTrack(this.track);
	 }
	this.nextTrack = function(){
		this.track++;
		if(this.track >= this.playlist.length){
			this.track = 0;
		}
		this.id_track        = this.playlist[this.track].id_track;
		this.artist          = this.playlist[this.track].artist;
		this.album           = this.playlist[this.track].album;
		this.title           = this.playlist[this.track].title;
		this.filename        = this.playlist[this.track].filename;
		this.cover           = this.playlist[this.track].cover;
		this.year            = this.playlist[this.track].year;
		this.genre           = this.playlist[this.track].genre;
		this.playTrack();
		// this.audioPlayer.src = this.playlist[this.track].filename;
		// this.audioPlayer.play();
	 }

	this.setCurrents = function(id_track,artist,album,title,filename,cover,year,genre){
		this.id_track = id_track;
		this.artist   = artist;
		this.album    = album;
		this.title    = title;
		this.filename = filename;
		this.cover    = cover;
		this.year     = year;
		this.genre    = genre;
	 }
	
	this.addToPlaylist = function(id_track,artist, album, title, filename, cover, year, genre){
		this.playlist.push({"id_track":id_track,
							"artist":filename,
							"album":album,
							"title":title,
							"filename":filename,
							"cover":cover,
							"year":year,
							"genre":genre});
		this.count++;
	 }
	this.clearPlaylist = function(){
		this.playlist = [];
		this.track = 0;
		this.count = 0;
	 }
	this.savePlaylist = function(user, name, social, callback){
		var data_pls = JSON.stringify(this.playlist);
		var data_pls = encodeURIComponent(data_pls);
		var data = "&id_user="+user+"&name="+name+"&social="+social+"&data="+data_pls+"&action=save_pls&";
		$.ajax({
			type:"POST",
			url:query,
			data:data,
			// dataType:"json",
			success: function(data){
				callback(data);
			}

	 	});
 	 }
	this.playlistExists = function(user, name, callback){
		var data = "&id_user="+user+"&name="+name+"&action=pls_exists&";
		$.ajax({
			type: "POST",
			data: data,
			url: query,
			success: function(data){
				callback(data);
				// if(data == "true"){
				// 	l("Client: Есть такой плейлист");
				// 	return true;
				// }
				// else{
				// 	l("Client: Нет такого плейлиста");
				// 	return false;
				// }
			}
		});
	 }
	this.getNamesPls = function(user, callback){
		l("test")
		var data = "&id_user="+user+"&action=get_name_pls";
		$.ajax({
			type:"POST",
			url:query,
			data:data,
			// dataType:"json",
			success: function(data){
				l(data);
				callback(data);
			}
		})
	 }
	this.loadPlaylist = function(user, name, callback){
		var data = "id_user="+user+"&name="+name+"&action=load_pls";
		$.ajax({
			type:"POST",
			data:data,
			url:query,
			success: function(data){
				callback(data);
			}
		})
	 }
	this.deletePlaylist = function(user, name, callback){
		var data = "&id_user="+user+"&name="+name+"&action=del_pls&";
		$.ajax({
			type: "POST",
			url: query,
			data: data,
			success: function(data){
				callback(data);
			}
		})
	 }
}