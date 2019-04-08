$(function(){
	var tb_username = $('#tb_username');
	var lb_msg = $('#lb_msg');
	var bt_submit = $('#bt_submit');

	var hash = window.location.hash;
	if(hash!=''){
		$.ajax({
			url: "/ajax/oauth_google_cmd.php?" + hash.substring(1),
			dataType: "json",
			beforeSend: function( xhr ) {
				lb_msg.show().text('Đang kiểm tra...');
				// spinner.show();
			},
			statusCode:{
				200:function(){
					// lb_msg.show().text('Đã kiểm tra xong');
				},
				404:function(){
					lb_msg.show().text('không tìm thấy địa chỉ đăng nhập');
					return false;
				},
				// 408:function(){
				// 	lb_msg.show().text('Server phản hồi quá chậm. hãy thử lại');
				// 	return false;
				// },
				500:function(){
					lb_msg.show().text('Đã xảy ra sự cố, hãy báo với admin');
					return false;
				}
			},
			success:function(data){
				if(data.email) tb_username.val(data.email);
				else tb_username.val('login gmail error');
				if(data.error==0){
					bt_submit.click(function(){
						window.location.href='/home.html';
					});
					setTimeout(function(){
						window.location.href="/home.html";
					},1000);
				}
				else{
					if(data.message){
						lb_msg.show().text(data.message);
					}
					bt_submit.attr('value','login other accout');
					bt_submit.click(function(){
						window.location.href='/login.php';
					});
				}
			}
		})
		.always(function() {
			// spinner.hide();
		});
	}
	else{
		window.location.href='/login.php';
	}
});