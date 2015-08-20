$(document).ready(function(){

	$('.spoiler_title').on('click', function(){
		$(this).parent().children('div.spoiler_text').toggle('normal');
	});
	$("#login_form").submit(function(){return false;});

	// on login
	$("#send_login").on("click", function(){	
		// alert('!');
		var data = $("#login_form").serialize();
		data += '&action=login&';
		// console.log(a);	

		$.ajax({
			type: 'POST',
			url: 'reg.php',
			data: data,
			success: function(data) {
				if(data == "true") {
					$("#login_form").fadeOut("fast", function(){
						setTimeout("document.location.href='index.php'",200);
					});
				}
					if(data == "false"){
						$('#error_login').toggle('normal').delay(1500).toggle('normal');
					}
			}

		});
  	});
});