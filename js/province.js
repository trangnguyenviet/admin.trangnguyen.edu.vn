var ProvinceEdit;
$(function(){
	var province_data = $('.province-data');
	ProvinceEdit={
		form: $('#form'),
		tbody: province_data.find('.tbody'),
		bt_add:province_data.find('.btn-add'),
		modal_detail:$('#modal-detail'),
		modal_title:$('#modal-detail .modal-title'),
		tb_id:$('#tb_id'),
		tb_name:$('#tb_name'),
		modal_message: $('#modal-detail .modal-message'),
		bt_save: $('#modal-detail .btn-save')
	};
	
	setTimeout(function(){
		ProvinceEdit.LoadData();
	},100);
	
	ProvinceEdit.modal = ProvinceEdit.modal_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	
	ProvinceEdit.bt_add.click(function(){
		ProvinceEdit.Fill_Popup(null);
	});
	
	ProvinceEdit.form.on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			ProvinceEdit.modal_message.show('Kiểm tra lại thông tin nhập');
		} else {
			ProvinceEdit.Save_Data();
		}
		return false;
	});
	
	ProvinceEdit.LoadData=function(){
		$.ajax({
			url: "/ajax/province_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list'},
			success: function(data) {
				if (data.error == 0) {
					ProvinceEdit.FillData(data.content);
				} else {
					if (data.error == 5) {
						window.location.href='/login.php';
					}
				}
			}
		});
	};
	
	ProvinceEdit.FillData=function(content){
		var html='';
		ProvinceEdit.result_obj = content;
		if(content){
			$.each(content,function(i,val){
				html+='<tr>';
				html+='<td class="text-center">'+ val._id +'</td>';
				html+='<td>'+ val.name +'</td>';
				html+='<td class="text-center"><a href="javascript:void(0);" tn-action="edit" onclick="javascript:return ProvinceEdit.ConfirmEdit(' + val._id + ');">Edit</a> | <a href="javascript:void(0);" tn-action="delete" onclick="javascript:return ProvinceEdit.ConfirmDelete(' + val._id + ');">Delete</a></td>';
				html+='</tr>';
			});
		}
		ProvinceEdit.tbody.html(html);
	};
	
	ProvinceEdit.ConfirmDelete=function(id){
		bootbox.confirm({
			message:'Bạn muốn xóa bản ghi này không?',
			size:'small',
			callback:function(result) {
				if(result){
					$.ajax({
						url: "/ajax/province_cmd.php",
						dataType: "json",
						type:"POST",
						data:{action:'delete',id:id},
						// beforeSend: function( xhr ) {
						// 	spinner.show();
						// },
						success:function(data){
							if(data.error==0){
								ProvinceEdit.LoadData();
								ShowMessError('Delete ok!');
							}
							else{
								Alert(data.message,function(){
									if(data.error==5){
										window.location.href='/login.php';
									}
								});
							}
						}
					});
					// .always(function() {
					// 	spinner.hide();
					// });
				}
			}
		});
		return false;
	};

	ProvinceEdit.ConfirmEdit=function(id){
		if(ProvinceEdit.result_obj!=null && ProvinceEdit.result_obj.length>0){
			for(var i=0;i<ProvinceEdit.result_obj.length;i++){
				var val = ProvinceEdit.result_obj[i];
				if(val._id==id){
					ProvinceEdit.Fill_Popup(val);
					break;
				}
			}
		}
		return false;
	};
	
	ProvinceEdit.Save_Data=function(){
		var id = parseInt(ProvinceEdit.tb_id.val());
		var name = ProvinceEdit.tb_name.val();
		$.ajax({
			url: "/ajax/province_cmd.php",
			dataType: "json",
			type:"POST",
			data:{action:'save',id:id,name:name},
			beforeSend: function(xhr) {
				spinner.show();
				ProvinceEdit.modal_message.show().text('Đang thao tác...');
			},
			success:function(data){
				if(data.error==0){
					ProvinceEdit.LoadData();
					if(id>0){
						ProvinceEdit.modal_message.show().text('Update thành công');
					}
					else{
						ProvinceEdit.modal_message.show().text('Insert thành công');
					}
					setTimeout(function(){
						ProvinceEdit.modal.modal('hide');
					}, 1000);
				}
				else{
					ProvinceEdit.modal_message.show().text(data.message);
					if(data.error==5){
						bootbox.alert({
							message:data.message,
							size: 'small',
							callback: function() {
								window.location.href='/login.php';
							}
						});
					}
				}
			}
		})
		.always(function() {
			spinner.hide();
			ProvinceEdit.bt_save.removeAttr('disabled');
		});
	};
	
	ProvinceEdit.Fill_Popup=function(obj){
		if(obj){
			ProvinceEdit.modal_title.text('Sửa thông tin tỉnh/thành phố');
			ProvinceEdit.tb_id.val(obj._id);
			ProvinceEdit.tb_name.val(obj.name);
		}
		else{
			ProvinceEdit.modal_title.text('Thêm mới thông tin tỉnh/thành phố');
			ProvinceEdit.tb_id.val('0');
			ProvinceEdit.tb_name.val('');
		}
		ProvinceEdit.modal_message.show().text('');
		ProvinceEdit.modal.modal('show');
		setTimeout(function(){
			ProvinceEdit.tb_name.focus();
		},200);
	};
});