//var spinner;

$(function(){
	spinner = $('#spinner');
	
	$('#bt_submit').click(function(){
		var user = $('#tb_username');
		var pass = $('#tb_password');
		var bRemember = $('#remember_me').is(':checked');
		var lb_msg = $('#lb_msg');
		var captcha = $('#g-recaptcha-response').val();
		
		lb_msg.hide().text('');
		
		var sUser = user.val();
		if(sUser==''){
			lb_msg.show().text('Hãy nhập username');
			user.focus();
			return false;
		}
		
		if(sUser.length < 3 || sUser.length > 20){
			lb_msg.show().text('Username phải từ 3 đến 20 ký tự');
			user.focus();
			return false;
		}
		
		if(!/^[a-z][a-z0-9_]{2,29}$/.test(sUser)){
			lb_msg.show().text('Username không có ký tự đặc biệt');
			user.focus();
			return false;
		}
		
		var sPass = pass.val();
		if(sPass==''){
			lb_msg.show().text('Hãy nhập password');
			pass.focus();
			return false;
		}
		
		if(sPass.length < 6 || sPass.length > 30){
			lb_msg.show().text('password phải từ 6 đến 30 ký tự');
			pass.focus();
			return false;
		}
		
		if(captcha!=undefined){
			if(captcha==''){
				lb_msg.show().text('Hãy xác nhận captcha');
				return false;
			}
		}
		
		$.ajax({
			url: "/ajax/login_cmd.php",
			type:"POST",
			//async: true,
			dataType: "json",
			data:{username:sUser,password:sPass,captcha:captcha,remember:(bRemember?1:0)},
			beforeSend: function( xhr ) {
				lb_msg.show().text('Đang kiểm tra...');
				spinner.show();
			},
			statusCode:{
				200:function(){
					//lb_msg.hide();
				},
				404:function(){
					lb_msg.show().text('không tìm thấy địa chỉ đăng nhập');
					return false;
				},
				500:function(){
					lb_msg.show().text('Đã xảy ra sự cố, hãy báo với admin');
					return false;
				}
			},
			success:function(data){
				//console.log(data);
				if(data.error==0){
					if(data.remember) window.location.href="/home.html?remember=" + data.remember;
					else window.location.href="/home.html";
					//return true;
				}
				else{
					grecaptcha.reset();
					lb_msg.show().text(data.message);
					pass.val('');
					user.focus();
					return false;
				}
			}
		})
		.always(function() {
			spinner.hide();
		});
	});
});