var SchoolEdit;
$(function(){
	var district_data = $('.district-data');
	SchoolEdit={
		ddl_province: $('#ddl_province'),
		ddl_district: $('#ddl_district'),
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
		SchoolEdit.LoadDistrict(function(){
			SchoolEdit.LoadData();
		});
	},100);
	
	SchoolEdit.modal = SchoolEdit.modal_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	
	SchoolEdit.ddl_province.change(function(){
		SchoolEdit.tbody.html('');
		SchoolEdit.ddl_district.html('');
		SchoolEdit.LoadDistrict(function(){
			SchoolEdit.LoadData();
		});
	});
	
	SchoolEdit.ddl_district.change(function(){
		SchoolEdit.LoadData();
	});
	
	SchoolEdit.bt_add.click(function(){
		SchoolEdit.Fill_Popup(null);
	});
	
	SchoolEdit.form.on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			SchoolEdit.modal_message.show('Kiểm tra lại thông tin nhập');
		} else {
			SchoolEdit.Save_Data();
		}
		return false;
	});
	
	SchoolEdit.LoadDistrict=function(callback){
		var province_id = SchoolEdit.ddl_province.val();
		$.ajax({
			url: "/ajax/district_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list',province_id:province_id},
			success: function(data) {
				if (data.error == 0) {
					var length = data.content.length;
					if(length>0){
						var html ='';
						for(var i=0;i<length;i++){
							var district = data.content[i];
							html+='<option value="' + district._id + '">' + district.name + '</option>';
						}
						SchoolEdit.ddl_district.html(html);
					}
					if(callback) callback();
				} else {
					if (data.error == 5) {
						window.location.href='/login.php';
					}
				}
			}
		});
	};
	
	SchoolEdit.LoadData=function(){
		var district_id = SchoolEdit.ddl_district.val();
		$.ajax({
			url: "/ajax/school_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list',district_id:district_id},
			success: function(data) {
				if (data.error == 0) {
					SchoolEdit.FillData(data.content);
				} else {
					if (data.error == 5) {
						window.location.href='/login.php';
					}
				}
			}
		});
	};
	
	SchoolEdit.FillData=function(content){
		var html='';
		SchoolEdit.result_obj = content;
		if(content){
			$.each(content,function(i,val){
				html+='<tr>';
				html+='<td class="text-center">'+ val._id +'</td>';
				html+='<td>'+ val.name +'</td>';
				html+='<td class="text-center"><a href="javascript:void(0);" tn-action="edit" onclick="javascript:return SchoolEdit.ConfirmEdit(' + val._id + ');">Edit</a> | <a href="javascript:void(0);" tn-action="delete" onclick="javascript:return SchoolEdit.ConfirmDelete(' + val._id + ');">Delete</a></td>';
				html+='</tr>';
			});
		}
		SchoolEdit.tbody.html(html);
	};
	
	SchoolEdit.ConfirmDelete=function(id){
		var district_id = SchoolEdit.ddl_district.val();
		bootbox.confirm({
			message:'Bạn muốn xóa trường này không?',
			size:'small',
			callback:function(result) {
				if(result){
					$.ajax({
						url: "/ajax/school_cmd.php",
						dataType: "json",
						type:"POST",
						data:{action:'delete',id:id,district_id:district_id},
						// beforeSend: function( xhr ) {
						// 	spinner.show();
						// },
						success:function(data){
							if(data.error==0){
								SchoolEdit.LoadData();
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

	SchoolEdit.ConfirmEdit=function(id){
		if(SchoolEdit.result_obj!=null && SchoolEdit.result_obj.length>0){
			for(var i=0;i<SchoolEdit.result_obj.length;i++){
				var val = SchoolEdit.result_obj[i];
				if(val._id==id){
					SchoolEdit.Fill_Popup(val);
					break;
				}
			}
		}
		return false;
	};
	
	SchoolEdit.Save_Data=function(){
		var id = parseInt(SchoolEdit.tb_id.val());
		var name = SchoolEdit.tb_name.val();
		var district_id = SchoolEdit.ddl_district.val();
		$.ajax({
			url: "/ajax/school_cmd.php",
			dataType: "json",
			type:"POST",
			data:{action:'save',id:id,name:name,district_id:district_id},
			beforeSend: function(xhr) {
				spinner.show();
				SchoolEdit.modal_message.show().text('Đang thao tác...');
			},
			success:function(data){
				if(data.error==0){
					SchoolEdit.LoadData();
					if(id>0){
						SchoolEdit.modal_message.show().text('Update thành công');
					}
					else{
						SchoolEdit.modal_message.show().text('Insert thành công');
					}
					setTimeout(function(){
						SchoolEdit.modal.modal('hide');
					}, 1000);
				}
				else{
					SchoolEdit.modal_message.show().text(data.message);
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
			SchoolEdit.bt_save.removeAttr('disabled');
		});
	};
	
	SchoolEdit.Fill_Popup=function(obj){
		if(obj){
			SchoolEdit.modal_title.text('Sửa thông tin trường học');
			SchoolEdit.tb_id.val(obj._id);
			SchoolEdit.tb_name.val(obj.name);
		}
		else{
			SchoolEdit.modal_title.text('Thêm mới thông tin trường học');
			SchoolEdit.tb_id.val('0');
			SchoolEdit.tb_name.val('');
		}
		SchoolEdit.modal_message.show().text('');
		SchoolEdit.modal.modal('show');
		setTimeout(function(){
			SchoolEdit.tb_name.focus();
		},200);
	};
});