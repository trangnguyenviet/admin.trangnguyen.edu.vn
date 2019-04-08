var DitrictEdit;
$(function(){
	var district_data = $('.district-data');
	DitrictEdit={
		ddl_province: $('#ddl_province'),
		form: $('#form'),
		tbody: district_data.find('.tbody'),
		bt_add: district_data.find('.btn-add'),
		modal_detail:$('#modal-detail'),
		modal_title:$('#modal-detail .modal-title'),
		tb_id:$('#tb_id'),
		tb_name:$('#tb_name'),
		modal_message: $('#modal-detail .modal-message'),
		bt_save: $('#modal-detail .btn-save')
	};
	
	setTimeout(function(){
		DitrictEdit.LoadData();
	},100);
	
	DitrictEdit.modal = DitrictEdit.modal_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	
	DitrictEdit.ddl_province.change(function(){
		DitrictEdit.LoadData();
	});
	
	DitrictEdit.bt_add.click(function(){
		DitrictEdit.Fill_Popup(null);
	});
	
	DitrictEdit.form.on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			DitrictEdit.modal_message.show('Kiểm tra lại thông tin nhập');
		} else {
			DitrictEdit.Save_Data();
		}
		return false;
	});
	
	DitrictEdit.LoadData=function(){
		var province_id = DitrictEdit.ddl_province.val();
		$.ajax({
			url: "/ajax/district_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list',province_id:province_id},
			success: function(data) {
				if (data.error == 0) {
					DitrictEdit.FillData(data.content);
				} else {
					if (data.error == 5) {
						window.location.href='/login.php';
					}
				}
			}
		});
	};
	
	DitrictEdit.FillData=function(content){
		var html='';
		DitrictEdit.result_obj = content;
		if(content){
			$.each(content,function(i,val){
				html+='<tr>';
				html+='<td class="text-center">'+ val._id +'</td>';
				html+='<td>'+ val.name +'</td>';
				html+='<td class="text-center"><a href="javascript:void(0);" tn-action="edit" onclick="javascript:return DitrictEdit.ConfirmEdit(' + val._id + ');">Edit</a> | <a href="javascript:void(0);" tn-action="delete" onclick="javascript:return DitrictEdit.ConfirmDelete(' + val._id + ');">Delete</a></td>';
				html+='</tr>';
			});
		}
		DitrictEdit.tbody.html(html);
	};
	
	DitrictEdit.ConfirmDelete=function(id){
		var province_id = DitrictEdit.ddl_province.val();
		bootbox.confirm({
			message:'Bạn muốn xóa bản ghi này không?',
			size:'small',
			callback:function(result) {
				if(result){
					$.ajax({
						url: "/ajax/district_cmd.php",
						dataType: "json",
						type:"POST",
						data:{action:'delete',id:id,province_id:province_id},
						// beforeSend: function( xhr ) {
						// 	spinner.show();
						// },
						success:function(data){
							if(data.error==0){
								DitrictEdit.LoadData();
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

	DitrictEdit.ConfirmEdit=function(id){
		if(DitrictEdit.result_obj!=null && DitrictEdit.result_obj.length>0){
			for(var i=0;i<DitrictEdit.result_obj.length;i++){
				var val = DitrictEdit.result_obj[i];
				if(val._id==id){
					DitrictEdit.Fill_Popup(val);
					break;
				}
			}
		}
		return false;
	};
	
	DitrictEdit.Save_Data=function(){
		var id = DitrictEdit.tb_id.val();
		var name = DitrictEdit.tb_name.val();
		var province_id = DitrictEdit.ddl_province.val();
		$.ajax({
			url: "/ajax/district_cmd.php",
			dataType: "json",
			type:"POST",
			data:{action:'save',id:id,name:name,province_id:province_id},
			beforeSend: function(xhr) {
				spinner.show();
				DitrictEdit.modal_message.show().text('Đang thao tác...');
			},
			success:function(data){
				if(data.error==0){
					DitrictEdit.LoadData();
					if(id>0){
						DitrictEdit.modal_message.show().text('Update thành công');
					}
					else{
						DitrictEdit.modal_message.show().text('Insert thành công');
					}
					setTimeout(function(){
						DitrictEdit.modal.modal('hide');
					}, 1000);
				}
				else{
					DitrictEdit.modal_message.show().text(data.message);
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
			DitrictEdit.bt_save.removeAttr('disabled');
		});
	};
	
	DitrictEdit.Fill_Popup=function(obj){
		if(obj){
			DitrictEdit.modal_title.text('Sửa thông tin quận/huyện');
			DitrictEdit.tb_id.val(obj._id);
			DitrictEdit.tb_name.val(obj.name);
		}
		else{
			DitrictEdit.modal_title.text('Thêm mới thông tin quận/huyện');
			DitrictEdit.tb_id.val('0');
			DitrictEdit.tb_name.val('');
		}
		DitrictEdit.modal_message.show().text('');
		DitrictEdit.modal.modal('show');
		setTimeout(function(){
			DitrictEdit.tb_name.focus();
		},200);
	};
});