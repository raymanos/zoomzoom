$(document).ready(function() {

	$(".lbjs").click(function(){
		alert("dd");
	});

    // $('#artist-select').listbox({
    //   //	'class':        'classSelect',   // класс, который будет добавлен
    //   	// 'searchbar':    true         // отображать строку поиска
    //   	// 'multiselect':  false           // не использовать множественный выбор
    // });



	$("#button1").click(function(){
		$(".mp3").addClass("png-play");
	});
	// var player = new MediaElementPlayer('#audio-player'/* Options */);
 // 	$('#audio-player').mediaelementplayer({
 // 	alwaysShowControls: true,
 // 	features: ['playpause','volume','progress'],
 // 	audioVolume: 'horizontal',
 // 	audioWidth: 400,
 // 	audioHeight: 120
 // 	});
 	$('.mp3-click').click(function(){
 		alert('!');
 	})
 });
