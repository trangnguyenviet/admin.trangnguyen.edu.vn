var EXAMTYPE={},EXAMS={};
$(function(){
	$('body').addClass('sidebar-collapse');

	var content_info = $('#content_info');
	var tb_id = $('#tb_id');
	var tb_name = $('#tb_name');
	var bt_add = $('#bt_add');
	var bt_save = $('#bt_save');
	//var lb_message = $('#lb_message');

	var ddl_exam_event_copy = $('#ddl_exam_event_copy');
	var ddl_class_copy = $('#ddl_class_copy');
	var modal_message_copy = $('#modal-message');
	var tb_id_copy = $('#tb_id_copy');

	//modal copy
	var modal_copy = $('#modal_copy').modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});

	$('#modal_copy form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message_copy.show('Kiểm tra lại thông tin nhập');
		} else {
			var id = parseInt(tb_id_copy.val());
			var exam_event_id = parseInt(ddl_exam_event_copy.val());
			var class_id = parseInt(ddl_class_copy.val());

			if(id == 0){
				Alert('hãy chọn đề để copy');
				return;
			}
			if(exam_event_id == 0){
				Alert('hãy chọn sự kiện');
				return;
			}
			if(class_id == 0){
				Alert('hãy chọn lớp');
				return;
			}

			$.ajax({
				url: "/ajax/exam_event_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action: 'copy', id: id, exam_event_id: exam_event_id, class_id: class_id},
				beforeSend: function(xhr) {
					spinner.show();
					modal_message_copy.show().text('Đang thao tác...');
				},
				success: function(data){
					if(data.error==0){
						modal_message_copy.show().text('Copy thành công');
						setTimeout(function(){
							modal_copy.modal('hide');
						}, 1000);
						EXAMTYPE.LoadDataType();
					}
					else{
						modal_message_copy.show().text(data.message);
						if(data.error==5){
							Alert(data.message, function() {
								window.location.href='/login.php';
							});
						}
					}
				}
			});
		}
		return false;
	});

	bt_add.click(function(){
		EXAMTYPE.FillInfo(null);
	});

	bt_save.click(function(){
		var name = tb_name.val().trim();
		var id = tb_id.val();
		if(name==''){
			Alert('Hãy nhập tên sự kiện',function(){
				setTimeout(function(){
					tb_name.focus();
				},100);
			});
			return false;
		}
		var name_ko_dau = write(name);
		$.ajax({
			url: "/ajax/exam_event_type_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'save',id:id,name:name,name_ko_dau:name_ko_dau},
			success: function(data) {
				if (data.error == 0) {
					ShowMessError('lưu thành công');
					EXAMTYPE.LoadDataType();
					if(data.id) tb_id.val(data.id);
				} else {
					if (data.error == 5) {
						window.location.href = '/login.php';
					}
				}
			}
		});
	});

	EXAMTYPE.LoadDataType = function(){
		$.ajax({
			url: "/ajax/exam_event_type_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list'},
			success: function(data) {
				if (data.error == 0) {
					var html='';
					var ddl_list = '<option value="0">Chọn sự kiện</option>';
					if(data.content){
						for(var i=0,info;info=data.content[i];i++){
							html+='<div class="row"> <div class="col-xs-12"> <div class="box box-info"> <div class="box-header"> <h3 class="box-title">'+info.name+'</h3> </div> <div class="box-body"> <div class="dataTables_wrapper form-inline dt-bootstrap news-data"> <div class="row"> <div class="col-sm-12"> <table class="table table-bordered table-hover dataTable"> <thead> <tr role="row"> <th class="tnw-4">Lớp 1</th> <th class="tnw-4">Lớp 2</th> <th class="tnw-4">Lớp 3</th> <th class="tnw-4">Lớp 4</th> <th class="tnw-4">Lớp 5</th> <th class="tnw-4">Lớp 6</th> </tr> </thead> <tbody class="tbody"> <tr class="text-center">';
							for(var j=1;j<=6;j++){
								html+='<td><div class="tn-exam-info" id="exam_'+info._id+'_'+j+'">(Chưa có nội dung)</div><div><a class="tn-exam-action-edit" onclick="EXAMS.ConfirmAdd('+info._id+','+j+')" href="javascript:void(0)">Sửa</a> | <a class="tn-exam-action-copy" onclick="Alert(\'Chưa có nội dung\');" href="javascript:void(0)">Copy</a> | <a class="tn-exam-action-delete" onclick="Alert(\'Chưa có nội dung\');" href="javascript:void(0)">Xóa</a></div></td>';
							}
							html+='</tr> </tbody> </table> </div> </div> <div class="row text-center">\
								<button type="button" onclick="javascript:return EXAMTYPE.FillInfo({_id:'+info._id+',name:\'' + info.name + '\'})" tn-action="add" class="btn btn-warning btn-edit">Sửa</button>\
								<button type="button" onclick="javascript:return EXAMTYPE.ConfirmDelete('+info._id+')" class="btn btn-danger btn-del">Xóa</button> </div> </div> </div> </div> </div> </div>';

							ddl_list+='<option value="'+info._id+'">'+info.name+'</option>';
						}
						EXAMS.LoadListInfo();
						ddl_exam_event_copy.html(ddl_list);
					}
					content_info.html(html);
					//ddl_exam_event_copy
				} else {
					if (data.error == 5) {
						window.location.href = '/login.php';
					}
				}
			}
		});
	};

	EXAMTYPE.LoadDataType();

	EXAMTYPE.ConfirmDelete = function(id){
		Confirm('<strong>Bạn muốn xóa sự kiện này không?</strong><br/>(sau khi xóa sẽ xóa hết bài thi của các lớp)',function(result){
			if(result){
				$.ajax({
					url: "/ajax/exam_event_type_cmd.php",
					type:"POST",
					dataType: "json",
					data:{action:'delete',id:id},
					success: function(data) {
						if (data.error == 0) {
							ShowMessError('Delete thành công');
							EXAMTYPE.LoadDataType();
						} else {
							Alert(data.message,function(){
								if (data.error == 5) {
									window.location.href='/login.php';
								}
							});
						}
					}
				});
			}
		});
	};

	EXAMTYPE.FillInfo = function(obj){
		if(obj){
			tb_id.val(obj._id);
			tb_name.val(obj.name);
		}
		else{
			tb_id.val(0);
			tb_name.val('');
		}
		setTimeout(function(){
			tb_name.focus();
		},100);
		return false;
	};

	EXAMS.LoadListInfo = function(){
		$.ajax({
			url: "/ajax/exam_event_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list'},
			success: function(data) {
				if (data.error == 0) {
					if(content = data.content){
						for(var i=0,info;info=content[i];i++){
							var div = $('#exam_' + info.type_id+'_'+info.class_id);
							div.html('Câu hỏi: <strong>'+info.content_count+'/'+info.play+'</strong> | Thời gian: <strong>'+info.time+'</strong>s');
							div = div.next();
							if(div.length>0){
								div.find('.tn-exam-action-edit').attr('onclick','EXAMS.ConfirmEdit('+info.type_id+','+info.class_id+','+info._id+')');
								div.find('.tn-exam-action-copy').attr('onclick','EXAMS.ConfirmCopy('+info._id+','+info.class_id+')');
								div.find('.tn-exam-action-delete').attr('onclick','EXAMS.ConfirmDelete('+info._id+')');
							}
						}
					}
				} else {
					Alert(data.message,function(){
						if (data.error == 5) {
							window.location.href='/login.php';
						}
					});
				}
			}
		});
	};

	EXAMS.ConfirmAdd = function(type_id,class_id){
		window.location.href='/exam/event_edit.html?type_id=' + type_id + '&class_id=' + class_id;
	};

	EXAMS.ConfirmEdit = function(type_id,class_id,id){
		window.location.href='/exam/event_edit.html?type_id=' + type_id + '&class_id=' + class_id+'&id=' + id;
	};

	EXAMS.ConfirmCopy = function(id,class_id){
		tb_id_copy.val(id);
		ddl_class_copy.val(class_id);
		modal_copy.modal('show');
	};

	EXAMS.ConfirmDelete = function(id){
		Confirm('<strong>Bạn muốn xóa bài thi này không?</strong><br/>(sau khi xóa sẽ không lấy được lại đề)',function(result){
			if(result){
				$.ajax({
					url: "/ajax/exam_event_cmd.php",
					type:"POST",
					dataType: "json",
					data:{action:'delete',id:id},
					success: function(data) {
						if (data.error == 0) {
							ShowMessError('Delete thành công');
							EXAMTYPE.LoadDataType();
						} else {
							Alert(data.message,function(){
								if (data.error == 5) {
									window.location.href='/login.php';
								}
							});
						}
					}
				});
			}
		});
	};
});