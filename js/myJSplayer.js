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
		this.playlist = new Array();
		this.count = 0;
		this.audioPlayer = $(audio_id)[0];
		this.audioPlayer.ontimeupdate = this.ontimeupdate;
		this.playerState = "pause";
		this.currentVolume = 0;
		//--------------------------
		this.currentIDTrack  = 0;
		this.currentNum      = 0;
		this.currentID       = "";
		this.currentArtist   = "";
		this.currentAlbum    = "";
		this.currentTitle    = "";
		this.currentGenre    = "";
		this.currentCover    = "";
		this.currentYear     = ""
		this.currentFilename = "";
		this.currentTrack = 0;// метка для плейлиста
		this.currentNamePlaylist = "";
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
	this.getInfo = function(){
		return ({"id_track":this.currentID,
				 "artist":this.currentArtist,
				 "album":this.currentAlbum,
				 "title":this.currentTitle,
				 "genre":this.currentGenre,
				 "cover":this.currentCover,
				 "year" :this.currentYear,
				 "filename":this.currentFilename});
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
 	this.playTrack = function(filename,num){
		this.playerState = "pause";
		// $("#player-play").attr("src","img/pause.png");
		// this.audioPlayer.src = filename;
		this.audioPlayer.src = this.playlist[num].filename; 
		this.audioPlayer.play();
		//формируем slider
		dur = this.audioPlayer.duration;
		// l(dur);
		// done = false;
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
		if(this.currentTrack != 0){
			this.currentTrack--;
		}
		else
			currentTrack = 0;
		this.currentFilename = this.playlist[this.currentTrack].filename;
		this.currentID       = this.playlist[this.currentTrack].id;
		this.currentTitle    = this.playlist[this.currentTrack].title;
		this.audioPlayer.src = this.playlist[this.currentTrack].filename;
		this.audioPlayer.play();
	 }
	this.nextTrack = function(){
		this.currentTrack++;
		if(this.currentTrack >= this.count){
			this.currentTrack = 0;
		}
		this.currentFilename = this.playlist[this.currentTrack].filename;
		this.currentID       = this.playlist[this.currentTrack].id;
		this.currentTitle    = this.playlist[this.currentTrack].title;
		this.audioPlayer.src = this.playlist[this.currentTrack].filename;
		this.audioPlayer.play();
	 }
	this.addToPlaylist = function(id,filename,title){
		this.playlist.push({"id":id,"filename":filename,"title":title});
		this.count++;
	 }
	this.setCurrents = function(id,artist,album,title,genre,filename,cover){
		this.currentID = id;
		this.currentArtist = artist;
		this.currentAlbum = album;
		this.currentTitle = title;
		this.currentGenre = genre;
		this.currentFilename = filename;
		this.currentCover = cover;
	 }

	this.clearPlaylist = function(){
		this.playlist = [];
		this.currentTrack = 0;
		this.count = 0;
	 }
	this.savePlaylist = function(user, name, callback){
		var data_pls = JSON.stringify(this.playlist);
		var data_pls = encodeURIComponent(data_pls);
		var data = "&user="+user+"&name="+name+"&data="+data_pls+"&action=save_pls&";
		console.log(data);
		console.log("save_pls:");
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
		var data = "&user="+user+"&name="+name+"&action=pls_exists&";
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
		var data = "&user="+user+"&action=get_name_pls";
		$.ajax({
			type:"POST",
			url:query,
			data:data,
			// dataType:"json",
			success: function(data){
				callback(data);
			}
		})
	 }
	this.loadPlaylist = function(user, name, callback){
		var data = "user="+user+"&name="+name+"&action=load_pls";
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
		var data = "&user="+user+"&name="+name+"&action=del_pls&";
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
function myJSplaylist(){

	this.deletePlaylist = function(name){
		var data = "&name="+name+"&action=del_pls&";
		$.ajax({
			type: "POST",
			url: query,
			data: data,
			success: function(data){
				if(data != "false"){
					l(data);
					return true;
				}
			}
		})
	 }
	// this.getNamesPls = function(){
	// 	var data = "&action=get_name_pls&";
	// 	$.ajax({
	// 		type: "POST",
	// 		url: query,
	// 		data: data,
	// 		success: function(data){
	// 			if (data != "false"){
	// 				l(data);
	// 				var pls = data.split(",");
	// 				l(pls);
	// 				$("#playlists").html("");
	// 				for(var i = 0; i < pls.length; i++){
	// 					l(i);
	// 					$("#playlists").append("<option class='opt-pls'>"+pls[i]+"</option>");
	// 				}
	// 				$("#playlists").selectmenu("refresh");
	// 			}
	// 			else{
	// 				l("Error!");
	// 			}
	// 		}
	// 	});	
	//  }



}