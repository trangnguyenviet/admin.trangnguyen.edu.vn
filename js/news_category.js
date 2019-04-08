var category_data;
var tbody;
var dataTables_info;
var pagination;
var btn_add;

var modal_category;
var modal_title;
var tb_id;
var tb_name;
var tb_rewrite;
var DDL_Parent;
var tb_sort;
var modal_message;
var btn_save;
var modal;
var result_obj;

$(function(){
	category_data = $('.category-data');
	tbody = category_data.find('.tbody');
	dataTables_info = category_data.find('.dataTables_info');
	pagination = category_data.find('.pagination');
	btn_add = category_data.find('.btn-add');

	modal_category = $('#modal-category');
	modal_title = modal_category.find('.modal-title');
	tb_id = $('#tb_id');
	tb_name = $('#tb_name');
	tb_rewrite = $('#tb_rewrite');
	DDL_Parent = $('#DDL_Parent');
	tb_sort = $('#tb_sort');
	modal_message = modal_category.find('.modal-message');
	btn_save = modal_category.find('.btn-save');

	// var validate = $('#form').validator('validate');
	modal = modal_category.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	modal.on('shown.bs.modal', function () {
		//validate = $('#form').validator('validate');
		tb_name.focus();
	});
	modal.on('hide.bs.modal', function () {
		// validate.validator('destroy');
		btn_save.removeAttr('disabled');
		modal_message.hide().text('');
	});

	tb_name.keyup(function(){
		var val = $(this).val();
		tb_rewrite.val(write(val));
	});
	tb_name.change(function(){
		var val = $(this).val();
		tb_rewrite.val(write(val));
	});

	btn_add.click(function(){
		Fill_Popup(null);
	});

	$('#form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message.show('Kiểm tra lại thông tin nhập');
		} else {
			// btn_save.attr('disabled','disabled');
			// modal.modal('hide');
			Save_Data();
		}
		return false;
	});
	
	LoadData();
});


function LoadData(){
	$.ajax({
		url: "/ajax/category_cmd.php",
		type:"POST",
		dataType: "json",
		data:{action:'list'},
		success: function(data) {
			if (data.error == 0) {
				FillData(data.content);
			} else {
				if (data.error == 5) {
					window.location.href='/login.php';
				}
			}
		}
	});
}
function FillData(content){
	var html='';
	var html_parent='<option value="0"></option>';
	result_obj = content;
	if(content){
		$.each(content,function(i,val){
			html+='<tr>';
			html+='<td>'+ val._id +'</td>';
			html+='<td>'+ val.name +'</td>';
			html+='<td>'+ val.parent_name +'</td>';
			html+='<td>'+ val.name_ko_dau +'</td>';
			html+='<td>'+ val.sort +'</td>';
			html+='<td><a href="javascript:void(0);" tn-action="edit" onclick="javascript:return ConfirmEdit(' + val._id + ');">Edit</a> | <a href="javascript:void(0);" tn-action="delete" onclick="javascript:return ConfirmDelete(' + val._id + ');">Delete</a></td>';
			html+='</tr>';

			if(val.parent_id==0) html_parent+='<option value="'+ val._id +'">' + val.name + '</option>';
		});
	}
	tbody.html(html);
	DDL_Parent.html(html_parent);
}
function ConfirmDelete(id){
	bootbox.confirm({
		message:'Bạn muốn xóa bản ghi này không?',
		size:'small',
		callback:function(result) {
			if(result){
				$.ajax({
					url: "/ajax/category_cmd.php",
					dataType: "json",
					type:"POST",
					data:{action:'delete',id:id},
					// beforeSend: function( xhr ) {
					// 	spinner.show();
					// },
					success:function(data){
						if(data.error==0){
							LoadData();
							ShowMessError('Delete ok!');
						}
						else{
							alert(data.message);
							if(data.error==5){
								window.location.href='/login.php';
							}
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
}

function ConfirmEdit(id){
	if(result_obj!=null && result_obj.length>0){
		$.each(result_obj,function(i,val){
			if(val._id==id){
				Fill_Popup(val);
				return false;
			}
		});
	}
	return false;
}

function Fill_Popup(obj){
	DDL_Parent.find('option').prop('disabled',false);
	if(obj){
		modal_title.text('Sửa thông tin danh mục');
		tb_id.val(obj._id);
		tb_name.val(obj.name);
		tb_rewrite.val(obj.name_ko_dau);
		tb_sort.val(obj.sort);
		DDL_Parent.val(obj.parent_id).find('option[value="' + obj._id + '"]').prop('disabled',true);
	}
	else{
		modal_title.text('Thêm mới thông tin danh mục');
		tb_id.val('0');
		tb_name.val('');
		tb_rewrite.val('');
		tb_sort.val('0');
		DDL_Parent.val('0');
	}
	modal.modal('show');
}

function Save_Data(){
	var id = parseInt(tb_id.val());
	var name = tb_name.val();
	var name_ko_dau = write(name);//tb_rewrite.val();
	var parent_id = DDL_Parent.val();
	var parent_name = DDL_Parent.find('option:selected').text();
	var sort = tb_sort.val();
	if(id == parent_id){
		return Alert('Không thể đặt nó làm chính con của nó!');
	}
	$.ajax({
		url: "/ajax/category_cmd.php",
		dataType: "json",
		type:"POST",
		data:{action:'save',id:id,name:name,name_ko_dau:name_ko_dau,parent_id:parent_id,parent_name:parent_name,sort:sort},
		beforeSend: function( xhr ) {
			spinner.show();
			modal_message.show().text('Đang thao tác...');
		},
		success:function(data){
			if(data.error==0){
				LoadData();
				if(id>0){
					modal_message.show().text('Update thành công');
				}
				else{
					modal_message.show().text('Insert thành công');
				}
				setTimeout(function(){
					modal.modal('hide');
				}, 1000);
			}
			else{
				modal_message.show().text(data.message);
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
		btn_save.removeAttr('disabled');
	});
}