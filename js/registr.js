$(document).ready(function(){

	$("#regform").submit(function(){return false;});

	// on login
	$("#send_reg").on("click", function(){	
		// alert('!');
		var data = $("#regform").serialize();
		data += '&action=registr&';
		console.log(data);	

		$.ajax({
			type: 'POST',
			url: 'reg.php',
			data: data,
			success: function(data) {
				console.log(data);
				if(data == "duplicate_login"){
					console.log("Duplicate login");
					var error_msg = "Такой логин уже существует!";
					$('#error_login').text(error_msg);
					$('#error_login').toggle('normal').delay(1500).toggle('normal');
				}
				if(data == "not_same_pass"){
					console.log("Not Same Passwords!");
					var text_msg = "Введенные пароли не совпадают!";
					$('#error_login').text(error_msg);
					$('#error_login').toggle('normal').delay(1500).toggle('normal');
				}
				if(data == "true") {
					console.log("TRUE");
					$("#regform").fadeOut("fast", function(){
						setTimeout("document.location.href='index.php'",200);		
					})
				}	

				if(data == "false"){
					console.log("FALSE");
					$('#error_login').toggle('normal').delay(1500).toggle('normal');
				}
			}

		});
  	});
});