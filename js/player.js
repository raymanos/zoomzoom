function l(text){
	console.log(text);
 }

var pl = new Object();

var num = 1;//для графики
var done = false;
//Инициализтруем плеер
var aP = new myJSplayer("#audio");
var _userID = $("#user_info").attr("id_user");
var _login  = $("#user_info").text();
aP.init();
getSettings();
/* Create a cache object */
var cache = new LastFMCache();
/* Create a LastFM object */
var lastfm = new LastFM({
  apiKey    : 'f21088bf9097b49ad4e7f487abab981e',
  apiSecret : '7ccaec2093e33cded282ec7bc81c6fca',
  cache     : cache
 });
/* Load some artist info. */
lastfm.artist.getInfo({artist: 'The xx'}, {success: function(data){
	l(data);
  /* Use data. */
}, error: function(code, message){
  /* Show error message. */
}});
 // Отправляет +1 к count трека 
function setCount(id_track,id_user){
	$.ajax({
		type:"POST",
		url :"query.php",
		data:"action=inc_count&id_user="+id_user+"&id_track="+id_track,
		success:function(data){
			l(data);
		}
	})
 }
 // Получаем настройки (пока только громкость)
function getSettings(){
	$.ajax({
		type:"POST",
		url :"query.php",
		data:"action=get_settings&id_user="+_userID,
		success:function(data){
			$("#volume-slider").slider("value",data);
			// aP.setVolume(data);
		}
	})
 }
 // Установить и отрисовать рейтинг трека
function getStars(id_track,id_user){
	$.ajax({
		type:"POST",
		url:"query.php",
		data:"action=get_stars&id_track="+id_track+"&id_user="+id_user,
		success: function(data){
			l(data);
			var stars = -1;
			if( parseInt(data) != -1 )
				stars = parseInt(data);
			var cur_id = "rating"+id_track;
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
// Обновляем  ИНФУ на ВСЕЙ странице
pl.updateInfo = function(){
	$("#audio-info").html("<b>"+aP.artist+" - "+aP.title+"</b>");
		$.ajax({
		type:"POST",
		url :"query.php",
		data:"action=get_count&id_user="+_userID+"&id_track="+aP.id_track,
		success:function(data){
			// l(data);
			var myCount = allCount = 0;
			
			if(data != "false"){
				data_json = JSON.parse(data);
				myCount = data_json.myCount;
				allCount = data_json.allCount;
			}
			$("#track-info").html("Количество воспроизведений<br>Своих: "+myCount+"<br>Вообще: "+allCount);
		}
	})
	
 }

 //формирует строку с треком + отрисовывает рейтинг
 // Отрисовка добавления трека к плейлисту
function addtoTable(id_track, artist, album, title, filename, cover, year, genre){
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
	item = "<tr><td>"+num+".</td><td><a href='#' id_track='"+id_track+
											     "' artist='"+artist+
												    "' num='"+num+
											      "' album='"+album+
											   "' filename='"+filename+
											      "' cover='"+cover+
										 "' class='mp3a'>"+title+"</a></td><td>"+rating_div+
										 "</td><td><img class='minus-mp3'src='img/minus.png'/></td></tr>";
	$("#track-table").append( $(item) );

	getStars(id_track,_userID);
 }
function addTo(id_track, artist, album, title, filename, cover, year, genre){
	aP.addToPlaylist(id_track, artist, album, title, filename, cover, year, genre);
	addtoTable(id_track, artist, album, title, filename, cover, year, genre);
 }
// Очищаем плейлист
pl.clearPlaylist = function(){
	num = 1;
	aP.clearPlaylist();
	$("#track-table").html("<table id='track-table' class='table-scroll'><tbody></tbody></table>");
 }
// Получаем имена плейлистлистов и заносим в список
pl.getNamesPls = function(data){
	// получаем JSON с данными name
	var data_pls = JSON.parse(data);
	l("Имена плейлистов с сервера:");
	l(data_pls);
	// визуальная часть
	$("#playlists").html("");
	for (var i =0; i < data_pls.length; i++)
	{
		if(data_pls[i].social == 1)
			$("#playlists").append("<option class='opt-pls'>*"+data_pls[i].name+"</option>");
		else
			$("#playlists").append("<option class='opt-pls'>"+data_pls[i].name+"</option>");
	}
	$("#playlists").selectmenu("refresh");

 }

pl.playPause = function(elem){
	switch(aP.playerState){
		case "pause":
			aP.pause();
			aP.playerState = "play";
			$("#player-play").attr("src","img/play2.png");
			console.log("vetka pause");
			break;
		case "play":
			aP.play();
			aP.playerState = "pause";
			$("#player-play").attr("src","img/pause.png");
			console.log("vetka play");
			break;
	}
 }
pl.nextTrack = function(){
	aP.nextTrack();
	if(aP.track != 0)
		num++;
	else
		num = 0;
	// l(aP.playlist);
	$("#pic-play").remove();
	$("a[num|='"+num+"']").before( $("<img id='pic-play' src='../img/play.png'/>") );
	pl.updateInfo();
	done = false;
 }
pl.prevTrack = function(){
	aP.prevTrack();
	if(aP.track != 0)
		num--;
	else
		num = 0;
	$("#pic-play").remove();
	$("a[num|='"+num+"']").before( $("<img id='pic-play' src='../img/play.png'/>") );
	pl.updateInfo();
	done = false;
 }
//Cобытия плеера
$( "#volume-slider" ).on( "slidestop", function( event, ui ) {
	aP.currentVolume = $(this).slider("value");
	$.ajax({
		type:"POST",
		url :"query.php",
		data:"action=set_settings&id_user="+_userID+"&volume="+aP.currentVolume,
		success:function(data){
			l(data);
			if( data == "true")
				l("В базу внесены изменения")
			else
				l("Ошибка");
		}
	})
 })
$("#audio")[0].ontimeupdate = function(){
	$("#audio-numbers").html(aP.updateNumbers());
	var duration    = Math.floor(aP.getDuration());
	var currentTime = Math.floor(aP.getCurrentTime()); 
	var roundtime   = Math.round(duration / 2);
	// var info = aP.getInfo();
	// Прошла половина трека
	if ( (currentTime >= roundtime ) && (done == false) ){
		l("Round!");
		// var user = $.trim($("#user_info").text());
		done = true;
		setCount( aP.id_track, _userID);
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
	//Обработчик нажатия на оценку
	$(document).on("click",".small-image",function(){
		var star = 0;
		var cur_id = $(this).parent("div").attr("id");
		var id_track = aP.id_track;//cur_id.split("rating")[1];
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
			data:"action=set_stars&star="+star+"&id_track="+id_track+"&id_user="+_userID,
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
	aP.getNamesPls(_userID, pl.getNamesPls);
	//Управление плеером ---------------------
	$("#player-play").click(function(){
		pl.playPause();
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
	 });
	$("#dialog-del-pls").dialog({
		autoOpen: false,
		modal: true,
		title: "Вопрос?",
		buttons: [{
			text: "Да, конечно!",
			click: function(){
				// Получаем имя плейлиста из select'а 
				currPls = $("#playlists").val();
				// Если плейлист социальный, у него есть *
				if( currPls.charAt(0) == '*')
					currPls = currPls.substring(1, currPls.length);
				// Запускаем удаление
				aP.deletePlaylist(_userID, currPls, function(data){
					if(data == "true")
						showError("Плейлист удачно удален!");
					else
					{
						l(data);
						showError("Какая-то ошибка :(");
					}
				});		
				$(this).dialog("close");	
				aP.getNamesPls(_userID, pl.getNamesPls);			
			}
		},
		{
			text: "Нет",
			click: function(){
				$( this ).dialog( "close" );
			}
		}]
	 });
	$("#button_add_folder").click(function(){
		$("#dialog-add-folder").dialog("open");
	 });
	$("#button_new_playlist").click(function(){
		$("#dialog-div").dialog("open");
	 });
	//Упаковка библиотеками
	$("#playlists").selectmenu({width:200});
	// Клик по плейлисту
	$("#playlists").on("selectmenuselect", function(event, ui){
		currPls = ui.item.label;
		// Если плейлист социальный, у него есть *
		if( currPls.charAt(0) == '*')
			currPls = currPls.substring(1, currPls.length);
		// запускаем загрузку
		aP.loadPlaylist(_userID, currPls, function(data){
			aP.currentNamePlaylist = currPls;
			var json = JSON.parse(data);
			// операции с плейлистом
			// aP.playlist = [];
			// num = 1;
			// //убираем лишнее из таблицы
			// $("#track-table").html("<table id='track-table'></table>"); 
			pl.clearPlaylist();
			for(var i=0; i < json.length; i++){
				if( json[i].filename != 0){
					addTo(  json[i].id_track,
						    json[i].artist,
							json[i].album,
							json[i].title,
							json[i].filename,
							json[i].cover,
							json[i].year,
							json[i].genre );
					num++; 
				}
			}
		});	
	 });
	// -------- DIALOGS ----------
	$("#dialog-add-folder").dialog({
		autoOpen:false,
		modal:true,
		dialogClass: "no-close",
		// minWidth:230,
		// maxHeigth:150,
		show: { effect: "fade", duration: 1000 },
		title:"Добавить каталог в коллекцию",
		buttons: [{
			text: "Ok",
			click: function(){
				$.ajax({
					type:"POST",
					url :"query.php",
					data:"action=add_folder&path="+$("#path").val(),
					success:function(data){
						l(data);
						$(this).dialog("close");
						showError("Папка добавлена!");

					}
				});
			}},{
				text: "Отмена",
				click: function(){
					$(this).dialog("close");
				}
		}]
	 });
	$("#dialog-message").dialog({
		autoOpen: false,
		modal: true,
		dialogClass: "no-close",
		title: "Ошибка",
		buttons: [{ text: "Ok", click: function() { $( this ).dialog( "close" ); } } ] 
	 });
	// Создание плейлиста
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
				if(num != 1){
					aP.playlistExists(_userID,$("#name-pls").val(), function(data){
						l("Пытаемся сохранить плейлист!");
						//Плейлиста с таким именем не существует, сохранянем
						l(data);
						if(data == "false"){
							l("Плейлиста с таким названием нет, пытаемся создать");
							// Сохраняем плейлист
							aP.savePlaylist(_userID,$("#name-pls").val(),$("#social-pls:checked").val(),function(data){
								l(data);
								if(data == "true"){
									// Закрываем диалог
									$("#dialog-div").dialog("close");
									showError("Плейлист успешно сохранен!");
									// Обновляем панель с плейлистами, добавляя новый
									aP.getNamesPls(_userID, pl.getNamesPls);
								}
								else
									showError("Возникла ошибка при создании плейлиста!");	
							});							
						}
						// Плейлист существует
						else
							showError("Такой плейлист уже существует!");	
					});
				}
				else
					showError("Плейлист пуст!");
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
			aP.setcurrentTime(ui.value);
		}
	 });

	$("#volume-slider").slider({
		value:10,
		min:0,
		max:10,
		change: function(event, ui){
			// l(ui.value/10);
			aP.setVolume(ui.value/10);
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
			
	//Удаление из плейлиста(из table)
	$(document).on("click", ".minus-mp3", function(){
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
		aP.currentGenre = $(this).html();
		var data = "action=get_artists&genre="+aP.currentGenre;
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
	// Клик по артисту
	$(document).on("click", ".artistClass .lbjs-item", function(){	
		aP.artist = encodeURIComponent($(this).html());
		var data = "&action=get_album&artist="+aP.artist;
		$.ajax({
			type: 'POST',
			url: 'query.php',
			data: data,
			success: function(data) {
				if (data != "false"){
					l(data);
					var data_json = JSON.parse(data);
					l(data_json);
					//удаляем все содержимое перед вставкой
					$(".albumClass .lbjs-list").html("");
					//внести в select из массива albums
					for(var i = 0; i < data_json.length; i++){
						$(".albumClass .lbjs-list").append( $("<div class='lbjs-item' album='"+data_json[i].album+"'>"+data_json[i].year + " " + data_json[i].album+"</div>") );
					}					
				}
				else
					alert("FALSE!");	
			}
		});

	 });
	// Клик по альбому
	$(document).on("click", ".albumClass  .lbjs-item",  function() {
		aP.album = $(this).attr("album");
		var data = "&action=get_tracks&artist="+aP.artist+"&album="+aP.album;
		$.ajax({
			type: 'POST',
			url: 'query.php',
			data: data,
			success: function(data) {
				if (data != "false"){
					l(data)
					var json = JSON.parse(data);
					// l(json)
					$(".tracksClass .lbjs-list").html("");
					for(var i=0; i < json.length; i++){
						item = "<div class='lbjs-item' id_track='"+json[i].id_track+
													  "' artist='"+json[i].artist+
													   "' album='"+json[i].album+
													   "' cover='"+json[i].cover+
													"' filename='"+json[i].filename+
													    "' year='"+json[i].year+
													   "' genre='"+json[i].genre+
													 "'>"+json[i].title+"</div>" 
						// $(".tracksClass .lbjs-list").append( $("<div class='lbjs-item' genre='"+data_json[i].Genre+"' artist='"+data_json[i].Artist+"' id_track='"+data_json[i].id_track+"' cover='"+data_json[i].Cover+"'  filename='"+data_json[i].Filename+"'>"+data_json[i].Title+"</div>") );
						$(".tracksClass .lbjs-list").append( $(item) );
					}
				}
				else 
					alert("FALSE!");
			}
		})
	 });
	// Двойной клик, выводит все треки альбома в плейлист
	$(document).on("dblclick", ".albumClass .lbjs-item", function(){
		aP.album = $(this).attr("album");
		data = "&action=get_tracks&artist="+aP.artist+"&album="+aP.album;
		$.ajax({
			type: 'POST',
			url: 'query.php',
			data: data,
			success: function(data) {
				if (data != "false"){
					var json = JSON.parse(data);
					$(".tracksClass .lbjs-list").html("");
					for(var i=0; i < json.length; i++)
					{
						item = "<div class='lbjs-item' id_track='"+json[i].id_track+
													  "' artist='"+json[i].artist+
													   "' album='"+aP.album+
												    "' filename='"+json[i].filename+
												       "' cover='"+json[i].cover+
												        "' year='"+json[i].year+
												       "' genre='"+json[i].genre+
												              "'>"+json[i].title+"</div>";
						$(".tracksClass .lbjs-list").append( $(item) );

						addTo(json[i].id_track,json[i].artist,aP.album,json[i].title,json[i].filename,json[i].cover,json[i].year,json[i].genre);
						num++;
					}
				}
				else
					alert("FALSE!");
			}
		})

	 })
	//Клик по треку в листбоксе->добавляем в таблицу ниже(плейлист)
	$(document).on("click", ".tracksClass .lbjs-item", function() {

		//Получаем трек, по которому кликнули
		id_track = $(this).attr("id_track");
		artist = $(this).attr("artist");
		album = $(this).attr("album");
		title    = $(this).html();
		filename = $(this).attr("filename");
		cover = $(this).attr("cover");
		year = $(this).attr("year");
		genre = $(this).attr("genre");
		addTo(id_track, artist, album, title, filename, cover, year, genre);
		num++;
	 });
	//Клик по трэку, ИГРАЕМ!!
	$(document).on("click", ".mp3a", function(){
		//вешаем картинку play
		$("#player-play").attr("src","img/pause.png");
		//убираем старую 
		$("#pic-play").remove();
		//вешаем новую
		$(this).before($("<img id='pic-play' src='../img/play.png'/>"));
		//получаем инфу о файле
		id_track = $(this).attr("id_track");
		artist   = $(this).attr("artist");
		album    = $(this).attr("album");
		title    = $(this).html();
		filename = $(this).attr("filename");
		cover    = $(this).attr("cover");
		year     = $(this).attr("year");
		genre    = $(this).attr("genre");
		//чтобы достать трек из логического плейлиста
		num      = $(this).attr("num");
		//ставим обложку
		$("#cover").attr("src",cover);
		aP.setCurrents(id_track, artist, album, title, filename, cover, year, genre);
		aP.track = num;
		aP.track--;
		aP.playTrack();
		//обновить player-info
		pl.updateInfo();
		//чтобы обнулить аяксовый запрос на inc_count
		done = false;
	 });
});