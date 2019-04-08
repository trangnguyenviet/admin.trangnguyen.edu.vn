var tb_username;
var img_captcha;
var tb_captcha;
var bt_submit;
var lb_msg;
var form;
var message_done;
$(function(){
	spinner = $('#spinner');
	tb_username=$('#tb_username');
	img_captcha=$('#img_captcha');
	tb_captcha=$('#tb_captcha');
	bt_submit=$('#bt_submit');
	lb_msg=$('#lb_msg');
	
	form=$('#form');
	message_done=$('#message_done');
	
	tb_username.focus();
	
	img_captcha.click(function(){
		reload_captcha();
	});
	
	function reload_captcha(){
		img_captcha.attr('src','captcha.php?v=' + Math.random());
		tb_captcha.val('');
		tb_captcha.focus();
	}
	
	bt_submit.click(function(){
		lb_msg.hide().text('');
		
		var sUser = tb_username.val();
		if(sUser==''){
			lb_msg.show().text('Hãy nhập username');
			tb_username.focus();
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
		
		var captcha = tb_captcha.val();
		if(captcha==''){
			lb_msg.show().text('Hãy nhập captcha');
			tb_captcha.focus();
			return false;
		}
		
		if(captcha.length < 3 || captcha.length > 15){
			lb_msg.show().text('captcha phải từ 3 đến 15 ký tự');
			captcha.focus();
			return false;
		}
		
		$.ajax({
			url: "/ajax/forgot_cmd.php",
			type:"POST",
			//async: true,
			dataType: "json",
			data:{username:sUser,captcha:captcha},
			beforeSend: function( xhr ) {
				lb_msg.show().text('Đang kiểm tra...');
				spinner.show();
			},
			statusCode:{
				200:function(){
					//lb_msg.hide();
				},
				404:function(){
					lb_msg.show().text('không tìm thấy địa chỉ thao tác');
					return false;
				},
				500:function(){
					lb_msg.show().text('Đã xảy ra sự cố, hãy báo với admin');
					return false;
				}
			},
			success:function(data){
				console.log(data);
				if(data.error==0){
					form.hide();
					var html='';
					if(data.send_mail){
						html='Thông tin lấy lại mật khẩu được gửi vào email: ' + data.email;
						html+='<br/>Hãy kiểm tra email để xem thông tin';
						html+='<br/>Token link sẽ hết hạn sau ' + data.expire + '(s)';
					}
					else{
						html='Không thể gửi thông tin vào email: ' + data.email;
					}
					message_done.show().html(html);
				}
				else{
					lb_msg.show().text(data.message);
					//tb_username.val('');
					//tb_username.focus();
					reload_captcha();
					return false;
				}
			}
		})
		.always(function() {
			spinner.hide();
		});
	});
});