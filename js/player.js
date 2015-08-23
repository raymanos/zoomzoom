function l(text){
	console.log(text);
 }

var pl = new Object();

var num = 1;//для графики
var currentNum = 0;//для логики
var done = false;
//Инициализтруем плеер
var audioPlayer = new myJSplayer("#audio");
audioPlayer.init();

pl.updateMusicStatictic = function(music_id,user,genre){
	//count
	//genre
	// l("Set genre: "+currentGenre);
	$.ajax({
		type:"POST",
		url:"query.php",
		data:"action=update_genre&user="+user+"&genre="+currentGenre,
		succes:function(data){
			// l(data);
		}
	})
 }
function getStars(music_id,user){
	$.ajax({
		type:"POST",
		url:"query.php",
		data:"action=get_stars&music_id="+music_id+"&user="+user,
		success: function(data){
			// l(data);
			var stars = parseInt(data);
			var cur_id = "rating"+music_id;
				if( stars == 10){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					star = 10;
				} else if ( stars == 9 ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					star = 9;
				} else if ( stars == 8 ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					star = 8;
				} else if ( stars == 7 ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star8").attr("src","img/black_star_part2.png");
					star = 7;
				} else if ( stars == 6){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star8").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star7").attr("src","img/black_star_part1.png");
					star = 6;
				} else if ( stars == 5 ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star8").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star7").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star6").attr("src","img/black_star_part2.png");
					star = 5;
				} else if ( stars == 4 ){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star3").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star4").attr("src","img/yellow_star_part2.png");
					star = 4;
				} else if ( stars == 3){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star3").attr("src","img/yellow_star_part1.png");
					star = 3;
				} else if ( stars == 2){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star2").attr("src","img/yellow_star_part2.png");
					star = 2;
				} else if (stars == 1){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					star = 1;
				}
		}
	})
 }
pl.updateInfo = function(){
	var info = audioPlayer.getInfo();
	var artist = info.artist;
	var title = info.title;
	$("#audio-info").html("<b>"+artist+" - "+title+"</b>");
 }
 //формирует строку с треком + отрисовывает рейтинг
function addtoTable(id_track,artist,title,filename,cover,num,genre){
	var rating_div = "<div id='rating"+id_track+"'>";
	var i = 0;
	var id_star = 0;
	for( var i = 0; i< 5; i++){
		id_star++;
		rating_div += "<img class='small-image part1' id='star"+id_star+"' src='img/black_star_part1.png' />";
		id_star++;
		rating_div += "<img class='small-image part2' id='star"+id_star+"' src='img/black_star_part2.png' />";
	}
	rating_div += "</div>";
	$("#track-table").append($("<tr><td>"+num+".</td><td><a href='#' genre='"+genre+"' id_track='"+id_track+"' num='"+(num-1)+"' artist='"+
		artist+"' cover='"+cover+"' filename='"+filename+"' class='mp3a'>"+
		title+"</a></td><td>"+rating_div+"</td><td><img class='minus-mp3'src='img/minus.png'/></td></tr>"));
	var user = $.trim($("#user_info").text());
	getStars(id_track,user);
 }
// pl.playlistExists = function(name){
// 	// l("Curr: "+currentNamePlaylist);
// 	l("FUNC WORK!!!");
// 	var data = "&name="+name+"&action=pls_exists&";
// 	var result = false;
// 	$.ajax({
// 		type: "POST",
// 		async: false,
// 		data: data,
// 		url: "query.php",
// 		success: function(data){
// 			l("Server: "+data);
// 			//true - существует
// 			//false - нету
// 			if(data == "true"){
// 				l("Client: Есть такой плейлист");
// 				var result = true;
// 			}
// 			else{
// 				l("Client: Нет такого плейлиста");
// 				var result = false;
// 			}
// 		}
// 	});
// 	return result;
//  }
pl.clearPlaylist = function(){
	// currentPlaylist = [];
	num = 1;
	audioPlayer.clearPlaylist();
	$("#track-table").html("<table id='track-table' class='table-scroll'><tbody></tbody></table>");
 }
pl.deletePlaylist = function(name){

 }
pl.getNamesPls = function(data){
	// получаем JSON с данными name,id_track
	var data_pls = JSON.parse(data);
	l("PLS:");
	l(data_pls);
	// визуальная часть
	$("#playlists").html("");
	for(var key in data_pls){
		$("#playlists").append("<option class='opt-pls'>"+key+"</option>");
	}
	$("#playlists").selectmenu("refresh");

	 }
pl.loadPlaylist = function(name){
	var data = "&name="+name+"&action=load_pls&";
	// l(data);
	$.ajax({
		type: "POST",
		url: "query.php",
		data: data,
		success: function(data){
			if(data != "false"){
				// l(data);
				var data_json = JSON.parse(data);
				// l(data_json);
				// операции с плейлистом
				currentPlaylist = [];
				num = 1;
				//убираем лишнее из таблицы
				$("#track-table").html("<table id='track-table'></table>");
				for(var i=0; i < data_json.length; i++){
					currentPlaylist.push({"Artist":data_json[i].artist,"Title":data_json[i].title,"Filename":data_json[i].filename,"Cover":data_json[i].cover});
					currentCount++;
					$("#track-table").append($("<tr><td>"+num+".</td><td><a href='#' num='"+(num-1)+"' artist='"+data_json[i].artist
						+"' cover='"+data_json[i].cover+"' filename='"+data_json[i].filename+"' class='mp3a'>"+data_json[i].title+"</a></td></tr>"));
					num++; 
				}
			}
		}
	});
 }
pl.playPause = function(elem){
	console.log("dddd");
	switch(audioPlayer.playerState){
		case "pause":
			audioPlayer.pause();
			audioPlayer.playerState = "play";
			$("#player-play").attr("src","img/play2.png");
			console.log("vetka pause");
			break;
		case "play":
			audioPlayer.play();
			audioPlayer.playerState = "pause";
			$("#player-play").attr("src","img/pause.png");
			console.log("vetka play");
			break;
	}
 }
pl.nextTrack = function(){
	audioPlayer.nextTrack();
	$("#pic-play").remove();
	$("a[num|='"+audioPlayer.currentTrack+"']").before( $("<img id='pic-play' src='../img/play.png'/>") );
	pl.updateInfo();
	done = false;
 }
pl.prevTrack = function(){
	audioPlayer.prevTrack();
	$("#pic-play").remove();
	$("#a[num|='"+audioPlayer.currentTrack+"']").before( $("<img id='pic-play' src='../img/play.png'/>") );
	pl.updateInfo();
	done = false;
 }
// pl.playTrack = function(elem, filename){
// 	playerState = "pause";
// 	$("#player-play").attr("src","img/pause.png");
// 	elem.src = filename; 
// 	elem.play();
// 	//формируем slider
// 	dur = elem.duration;
// 	l(dur);
// 	done = false;
//  }
//Cобытия плеера
$("#audio")[0].ontimeupdate = function(){
	$("#audio-numbers").html(audioPlayer.updateNumbers());
	var duration = Math.floor(audioPlayer.getDuration());
	var currentTime = Math.floor(audioPlayer.getCurrentTime()); 
	var roundtime = Math.round(duration / 2);
	var info = audioPlayer.getInfo();
	if ( (currentTime >= roundtime ) && (done == false) ){
		var user = $.trim($("#user_info").text());
		done = true;
		pl.updateMusicStatictic(info.id_track,user,info.genre);
		$.ajax({
			type:"POST",
			url:"query.php",
			data:"action=inc_count&user="+user+"&music_id="+info.id_track,
			success: function(data){
				l(data);
			}
		})
	}
	$("#audio-slider").slider("option","max",duration);
	$("#audio-slider").slider("value",currentTime);
 }
$("#audio")[0].onended = function(){
	done = false;
	pl.nextTrack();
 }
 //функция показа ошибки
 function showError(text){
 	$("#dialog-message").html(text);
 	$("#dialog-message").dialog("open");
 }
// --------------------------------------------------------------------------------------------
		$(document).ready(function() {
			//получаем имя пользователя 
			var user = $.trim($("#user_info").text());
			//Обработчик нажатия на оценку
			$(document).on("click",".small-image",function(){
				var star = 0;
				l($(this).attr("id"));
				var cur_id = $(this).parent("div").attr("id");
				l(cur_id);
				var music_id = cur_id.split("rating")[1];
				var user = $.trim($("#user_info").text());
				l(music_id);
				if( $(this).attr("id") == "star10" ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					star = 10;
				} else if ( $(this).attr("id") == "star9" ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					star = 9;
				} else if ( $(this).attr("id") == "star8" ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					star = 8;
				} else if ( $(this).attr("id") == "star7" ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star8").attr("src","img/black_star_part2.png");
					star = 7;
				} else if ( $(this).attr("id") == "star6" ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star8").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star7").attr("src","img/black_star_part1.png");
					star = 6;
				} else if ( $(this).attr("id") == "star5" ){
					$("#"+cur_id+" .part1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star10").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star9").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star8").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star7").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" #star6").attr("src","img/black_star_part2.png");
					star = 5;
				} else if ( $(this).attr("id") == "star4" ){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star3").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star4").attr("src","img/yellow_star_part2.png");
					star = 4;
				} else if ( $(this).attr("id") == "star3" ){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star2").attr("src","img/yellow_star_part2.png");
					$("#"+cur_id+" #star3").attr("src","img/yellow_star_part1.png");
					star = 3;
				} else if ( $(this).attr("id") == "star2" ){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					$("#"+cur_id+" #star2").attr("src","img/yellow_star_part2.png");
					star = 2;
				} else if ( $(this).attr("id") == "star1" ){
					$("#"+cur_id+" .part1").attr("src","img/black_star_part1.png");
					$("#"+cur_id+" .part2").attr("src","img/black_star_part2.png");
					$("#"+cur_id+" #star1").attr("src","img/yellow_star_part1.png");
					star = 1;
				}
				$.ajax({
					type:"POST",
					url:"query.php",
					data:"action=set_stars&star="+star+"&music_id="+music_id+"&user="+user,
					success: function(data){
						l(data);
					}
				});
			 });
			//Клик по #exit
			$("#exit").click(function(){
				window.location.href="exit.php";
			 });
			$("#tabs").tabs();
			//обложка по умолчанию
			$("#cover").attr("src","img/un_cover.png");
			//Считываем плейлисты
			audioPlayer.getNamesPls(user, pl.getNamesPls);
			//Управление плеером ---------------------
			$("#player-play").click(function(){
				pl.playPause();
				// audioPlayer.playPause();
			 });
			$("#player-next").click(function(){
				pl.nextTrack();
			 });
			$("#player-prev").click(function(){
				pl.prevTrack();
			 });
			$("#button_new_list").click(function(){
				pl.clearPlaylist();
			 });
			//Удаление плейлистов из базы
			$("#button_del_playlist").click(function(){
				$("#dialog-del-pls").html("Точно удалить этот плейлист?");
				$("#dialog-del-pls").dialog("open");
				// pl.deletePlaylist(currentNamePlaylist);
				// l($("#playlists span.ui-selectmenu-text"));
				// l("Playlist: "+currentNamePlaylist);
			 });
			$("#dialog-del-pls").dialog({
				autoOpen: false,
				modal: true,
				title: "Вопрос?",
				buttons: [{
					text: "Да, конечно!",
					click: function(){
						l(audioPlayer.currentNamePlaylist);
						audioPlayer.deletePlaylist(user, audioPlayer.currentNamePlaylist, function(data){
							if(data == "true")
								showError("Плейлист удачно удален!");
							else
								showError("Какая-то ошибка :(");
						});		
						$(this).dialog("close");	
						audioPlayer.getNamesPls(user, pl.getNamesPls);			
					}
				},{
					text: "Нет",
					click: function(){
						$( this ).dialog( "close" );
					}
				}]
			 });
			$("#button_new_playlist").click(function(){
				l(audioPlayer.playlist);
				$("#dialog-div").dialog("open");
			 });
			$("#button_love").click(function(){
				// l(currentID);
				// l();
				var user = $.trim($("#user_info").text());
				$.ajax({
					type:"POST",
					url:"query.php",
					data:"action=love_track&user="+user+"&id="+currentID,
					success: function(data){
						if(data != "false"){
							// l(data)
						}
						else {
							// l("false");
						}
					}
				});
			 });
			//Упаковка библиотеками
			$("#playlists").selectmenu({width:135});
			$("#playlists").on("selectmenuselect", function(event, ui){
				audioPlayer.loadPlaylist(user,ui.item.label, function(data){
					audioPlayer.currentNamePlaylist = ui.item.label;
					l("Current.PLS: "+audioPlayer.currentNamePlaylist)
					l(data);
					var data_json = JSON.parse(data);
					l(data_json);
					// операции с плейлистом
					audioPlayer.playlist = [];
					num = 1;
					// //убираем лишнее из таблицы
					$("#track-table").html("<table id='track-table'></table>");
					for(var i=0; i < data_json.length; i++){
						audioPlayer.playlist.push({"id":data_json[i].id,"title":data_json[i].title,"filename":data_json[i].filename,"cover":data_json[i].cover});
						audioPlayer.currentCount++;
						addtoTable(data_json[i].id,
								   data_json[i].artist,
								   data_json[i].title,
								   data_json[i].filename,
								   data_json[i].cover,
								   num,
								   data_json[i].genre);
						// $("#track-table").append($("<tr><td>"+num+".</td><td><a href='#' num='"+(num-1)+"' artist='"+data_json[i].artist
						// 	+"' cover='"+data_json[i].cover+"' filename='"+data_json[i].filename+"' class='mp3a'>"+data_json[i].title+"</a></td></tr>"));
						num++; 
					}

				});
				// l(ui.item.label);
				audioPlayer.currentNamePlaylist = ui.item.label;
			 });
			// -------- DIALOGS ----------
			$("#dialog-message").dialog({
				autoOpen: false,
				modal: true,
				dialogClass: "no-close",
				title: "Ошибка",
				buttons: [{ text: "Ok", click: function() { $( this ).dialog( "close" ); } } ] 
			 });
			$("#dialog-div").dialog({
				autoOpen:false,
				modal:true,
				dialogClass: "no-close",
				// minWidth:230,
				// maxHeigth:150,
				show: { effect: "fade", duration: 1000 },
				title:"Название плейлиста",
				buttons: [{
					text: "Ok",
					click: function(){
						audioPlayer.playlistExists(user,$("#name-pls").val(), function(data){
							// l("stage1");
							//Плейлиста с таким именем не существует, сохранянем
							if(data == "false"){
								// l("stage2");
								// Сохраняем плейлист
								audioPlayer.savePlaylist(user,$("#name-pls").val(),function(data){
									l(data);
									if(data == "true"){
										// l("stage3");
										// Закрываем диалог
										$("#dialog-div").dialog("close");
										showError("Плейлист упешно сохранен!");
										// Обновляем панель с плейлистами, добавляя новый
										audioPlayer.getNamesPls(user, pl.getNamesPls);
									}
									else {
										showError("Возникла ошибка при создании плейлиста!");
									}
								});							
							}
							// Плейлист существует
							else{
								showError("Такой плейлист уже существует!");
							}
						});



					}
				},{
					text: "Отмена",
					click: function(){
						$(this).dialog("close");
					}
				}]
			 });

			$("#audio-slider").slider({
				value:0, 
				min: 0,
				slide: function(event, ui){
					// l("ddddddddddffffffff");
					audioPlayer.setcurrentTime(ui.value);
				}
			 });
			$("#volume-slider").slider({
				value:10,
				min:0,
				max:10,
				change: function(event, ui){
					// l(ui.value/10);
					audioPlayer.setVolume(ui.value/10);
				}
			 });
			// ListBox'ы
			$("#genre-select").listbox({
				'class': 'genreClass',
				'searchbar': true
			 });
			$('#artist-select').listbox({
				'class': 'artistClass',
				'searchbar':  true
			 });
			$("#albums-select").listbox({
				'class': 'albumClass',
				'searchbar': true
			 });
			$("#tracks-select").listbox({
				'class': 'tracksClass',
				'searchbar': true
			 });
			//-----------------------------------------  
			
			//Удаление из плейлиста(из table)
			$(document).on("click", ".minus-mp3",              function(){
				//как добраться до конкретного td
				var td = $(this).parent().parent().children().find(".mp3a");
				// var tbody = $(this).parent().parent().parent().children().find("td:first-child");
				//вычислить num
				var num_to_del = $(td).attr("num");
				num_to_del = parseInt(num_to_del);
				//удаляем элемент из currentPlaylist
				currentPlaylist.splice(num_to_del,1);
				currentCount--;
				num--;
				//удалить весь tr
				var tr = $(this).parent().parent();
				$(tr).remove();
				//.find("td:first-child");
				//пересчитать num для таблицы
				// var tbody = $(this).parent().parent().parent().children().find("td:first-child");
				var tbody = $("tbody td:first-child");
				// l(tbody);
				// num = 0;
				$(tbody).each(function(index){
					$(this).text(index+1);
				});
			 });
				// Genre click
				$(".genreClass .lbjs-item").on("click", function(){
					var currentGenre = $(this).html();
					var data = "action=get_artists&genre="+currentGenre;
					$.ajax({
						type: "POST",
						url: "query.php",
						data: data,
						success: function(data){
							if( data != "false" ){
								var artists = data.split(",");
								// l(artists);
								$(".artistClass .lbjs-list").html("");
								// l(artists.length);
								for(var i = 0; i < artists.length; i++){
									// l(artists[i]);
									$(".artistClass .lbjs-list").append( $("<div class='lbjs-item'>"+artists[i]+"</div>"));
								}
							}
							else
								alert("FALSE!");
						}
					});

				});
				// Artist Click
				// $('.artistClass .lbjs-item').on("click",function(){
			$(document).on("click", ".artistClass .lbjs-item", function(){	
					// alert("Artist: "+$(this).html());
					audioPlayer.currentArtist = $(this).html();
					l(audioPlayer.currentArtist);
					var data = "&artist="+audioPlayer.currentArtist;
					data += '&action=get_album&';
					$.ajax({
						type: 'POST',
						url: 'query.php',
						data: data,
					success: function(data) {
						if (data != "false"){
						 // alert("DATA: "+data);
						 //массив albums
						 var albums = data.split(',');
						 //удаляем все содержимое перед вставкой
						 $(".albumClass .lbjs-list").html("");
						 //внести в select из массива albums
						 for(var i = 0; i < albums.length; i++){
							$(".albumClass .lbjs-list").append( $("<div class='lbjs-item'>"+albums[i]+"</div>") );
						 }
					// $(".albumClass .lbjs-list").css("height","400");
					
						}
						else{
						 alert("FALSE!");
						}
					}
					});

			 	 });
				// Album click!
			$(document).on("click", ".albumClass  .lbjs-item",  function() {
				var num = 0;
				//alert("Album: "+$(this).html());
				currentAlbum = $(this).html();
				var data = "&album="+$(this).html();
				data += "&action=get_tracks&";
				$.ajax({
					type: 'POST',
					url: 'query.php',
					data: data,
				success: function(data) {
					if (data != "false"){
						//_,_,_@_,_,_
						//alert(data);
						var data_json = JSON.parse(data);
						$(".tracksClass .lbjs-list").html("");
						for(var i=0; i < data_json.length; i++){
							$(".tracksClass .lbjs-list").append( $("<div class='lbjs-item' genre='"+data_json[i].Genre+"' id_track='"+data_json[i].id_track+"' cover='"+data_json[i].Cover+"'  filename='"+data_json[i].Filename+"'>"+data_json[i].Title+"</div>") );
						}
					}
					else {
						alert("FALSE!");
					}
				}
				})
			 });
				//Клик по треку в листбоксе->добавляем в таблицу ниже(плейлист)
			$(document).on("click", ".tracksClass .lbjs-item", function() {
				//Получаем трек, по которому кликнули
				var title = $(this).html();
				//currentTitle = title;
				var filename = $(this).attr("filename");
				var cover = $(this).attr("cover");
				currentGenre = $(this).attr("genre");
				var id_track = $(this).attr("id_track");
				// операции с плейлистом
				console.log(id_track, filename,title);
				audioPlayer.addToPlaylist(id_track,filename,title);
				// currentPlaylist.push({
				//  	"id_track":id_track,
				//  	"Artist":currentArtist,
				//  	"Title":title,
				//  	"Filename":filename,
				//  	"Cover":cover,
				//  	"currentCount":currentCount});
				 // currentCount++;
				 // l(currentPlaylist);
				 //alert("Track: "+track+" Filename: "+filename);
				 //Добавляем в таблицу
				 addtoTable(id_track,audioPlayer.currentArtist,title,filename,cover,num,currentGenre);
				 // l(audioPlayer.playlist);
				 // getStars()
				 //$("#track-table").append($("<tr><td>"+num+".</td><td><a href='#' num='"+(num-1)+"' artist='"+currentArtist+"' cover='"+cover+"' filename='"+filename+"' class='mp3a'>"+title+"</a></td></tr>"));
				 num++; 
			 });
			 //Клик по трэку, ИГРАЕМ!!
			$(document).on("click", ".mp3a",                   function(){
				//вешаем картинку play
				$("#player-play").attr("src","img/pause.png");
				//убираем старую 
				$("#pic-play").remove();
				//вешаем новую
				$(this).before($("<img id='pic-play' src='../img/play.png'/>"));
				//получаем инфу о файле
				var id =     $(this).attr("id_track");
				var artist = $(this).attr("artist");
				var title =  $(this).text();
				var genre =  $(this).attr("genre");
				var fname =  $(this).attr("filename");
				var cover = $(this).attr("cover");
				//чтобы достать трек из логического плейлиста
				currentNum = $(this).attr("num");
				//ставим обложку
				$("#cover").attr("src",cover);
				audioPlayer.setCurrents(id,artist,"",title,genre,fname,cover);
				audioPlayer.playTrack(fname,currentNum);
				//обновить player-info
				pl.updateInfo();
				//чтобы обнулить аяксовый запрос на inc_count
				done = false;
			 });
		 });