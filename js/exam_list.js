var Exams;
$(function(){
	Exams = {
		ddl_exam_type:$('#ddl_exam_type'),
		tb_total_round:$('#tb_total_round'),
		tb_current_round:$('#tb_current_round'),
		tb_payment_round: $('#tb_payment_round'),
		bt_save:$('#bt_save'),
		content_info:$('#content_info'),
		ddl_class_id:$('#ddl_class_id'),
		tbody: $('#content_info .tbody')
	};
	
	//modal
	var modal_copy = $('#modal_copy');
	Exams.modal_copy = modal_copy.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	Exams.modal_copy.control = {
		tb_id_copy: $('#tb_id_copy'),
		ddl_exam_type_copy: $('#ddl_exam_type_copy'),
		ddl_class_copy: $('#ddl_class_copy'),
		tb_round_copy: $('#tb_round_copy'),
		tb_test_copy: $('#tb_test_copy'),
		modal_message: $('#modal_copy .modal-message'),
		btn_copy: $('#modal_copy .btn-copy')
	};
	Exams.modal_copy.control.btn_copy.click(function(){
		Exams.Copy();
		return false;
	});
	//end modal
	
	Exams.game_type = window.game_type;
	
	//event
	Exams.bt_save.click(function(){
		Exams.SaveInfo();
	});
	Exams.ddl_exam_type.change(function(){
		Exams.LoadInfo();
		GoScrollTo('#ddl_exam_type');
	});
	Exams.ddl_class_id.change(function(){
		Exams.LoadExamInfo();
		GoScrollTo('#ddl_class_id');
	});
	//end event
	
	Exams.LoadInfo = function(){
		var exam_type = Exams.ddl_exam_type.val();
		var key_total_round = window.total_round + exam_type,
		key_current_round = window.current_round + exam_type,
		key_payment_round = window.payment_round + exam_type,
		
		keys = key_total_round + ',' + key_current_round + ',' + key_payment_round;
		$.ajax({
			url: "/ajax/param_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'list',keys:keys},
			success: function(data) {
				if (data.error == 0) {
					if(data.content){
						Exams.tb_total_round.val(data.content[key_total_round]?data.content[key_total_round]:0);
						Exams.tb_current_round.val(data.content[key_current_round]?data.content[key_current_round]:0);
						Exams.tb_payment_round.val(data.content[key_payment_round]?data.content[key_payment_round]:0);
						Exams.LoadExamInfo();
					}
				} else {
					if (data.error == 5) {
						window.location.href='/login.php';
					}
				}
			}
		});
	};
	
	Exams.LoadExamInfo = function(){
		var type_id = Exams.ddl_exam_type.val();
		var class_id = Exams.ddl_class_id.val();
		var total_round = parseInt(Exams.tb_total_round.val());
		var html = '';
		if(total_round>0){
			for(var i=1;i<=total_round;i++){
				html += '<tr class="text-center">';
				html += '<td><strong>' + i + '</strong></td>';
				for(var j=1;j<=3;j++){
					html += '<td id="td_' + i + '_' + j + '"><div class="tn-exam-info">(Chưa có nội dung)</div><div><a class="tn-exam-action-edit" onclick="Exams.ConfirmAdd(' + i + ',' + j + ')" href="javascript:void(0)">Sửa</a> | <a class="tn-exam-action-copy" onclick="Alert(\'Chưa có nội dung\');" href="javascript:void(0)">Copy</a> | <a class="tn-exam-action-download" onclick="Alert(\'Chưa có nội dung\');" href="javascript:void(0)">Download</a> | <a class="tn-exam-action-delete" onclick="Alert(\'Chưa có nội dung\');" href="javascript:void(0)">Xóa</a></div></td>';
				}
				html += '</tr>';
			}
			
			$.ajax({
				url: "/ajax/exam_cmd.php",
				type:"POST",
				dataType: "json",
				data:{action:'list',type_id:type_id,class_id:class_id},
				success: function(data) {
					if (data.error == 0) {
						if(data.content){
							$.each(data.content,function(index,value){
								var td = $('#td_' + value.round_id + '_' + value.test);
								var exam_type = Exams.game_type[value.game_id];
								td.find('.tn-exam-info').html('<strong>' + exam_type.game_name + '</strong> | ' + exam_type.content_name + ': <strong>' + value.content_count + '</strong> | Thời gian: <strong>' + value.time + '</strong>s');
								td.find('.tn-exam-action-edit').attr('onclick','Exams.ConfirmEdit(' + value._id + ');');
								td.find('.tn-exam-action-delete').attr('onclick','Exams.ConfirmDelete(' + value._id + ');');
								td.find('.tn-exam-action-copy').attr('onclick','Exams.ConfirmCopy(' + value._id + ',' + value.round_id + ',' + value.test + ');');
								td.find('.tn-exam-action-download').attr('onclick','Exams.ConfirmDownload(' + value.game_id + ',' + value._id + ',' + value.round_id + ',' + value.test + ');');
							});
						}
					} else {
						if (data.error == 5) {
							window.location.href='/login.php';
						}
					}
				}
			});
		}
		Exams.tbody.html(html);
	};
	
	Exams.SaveInfo = function(){
		var current_round = Exams.tb_current_round.val();
		Confirm('Bạn muốn mở vòng hiện tại là ' + current_round + ' không?',function(result){
			if(result){
				var type_id = Exams.ddl_exam_type.val();
				var key_current_round = window.current_round + type_id;
				$.ajax({
					url: "/ajax/param_cmd.php",
					type:"POST",
					dataType: "json",
					data:{action:'save',type_id:type_id,id:key_current_round,value:current_round},
					success: function(data) {
						if (data.error == 0) {
							console.log(data.content_nodejs);
							Alert('Save thành công');
						} else {
							Alert(data.message,function(){
								if (data.error == 5) {
									window.location.href='/login.php';
								}
							});
						}
					}
				});
				var key_payment_round = window.payment_round + type_id;
				var payment_round = Exams.tb_payment_round.val();
				$.ajax({
					url: "/ajax/param_cmd.php",
					type:"POST",
					dataType: "json",
					data:{action:'save',type_id:type_id,id:key_payment_round,value:payment_round},
					success: function(data) {
						console.log(data);
					}
				});
			}
		});
	};
	
	Exams.ConfirmAdd = function(round_id,test_id){
		var type_id = Exams.ddl_exam_type.val(),
		class_id = Exams.ddl_class_id.val();
		window.location.href = '/exam/edit.html?id=0&type_id=' + type_id + '&class_id=' + class_id + '&round_id=' + round_id + '&test_id=' + test_id;
	};
	
	Exams.ConfirmEdit = function(id){
		window.location.href = '/exam/edit.html?id=' + id;
		/*
		Confirm('Chuyển đến trang chỉnh sửa?',function(result){
			if(result){
				window.location.href = '/?page=exam/edit&id=' + id;
			}
		});
		*/
	};
	
	Exams.ConfirmDelete = function(id){
		Confirm('<strong>Bạn muốn xóa bài thi này không?</strong><br/>(sau khi xóa không thể lấy lại thông tin bài thi)',function(result){
			if(result){
				$.ajax({
					url: "/ajax/exam_cmd.php",
					type:"POST",
					dataType: "json",
					data:{action:'delete',id:id},
					success: function(data) {
						if (data.error == 0) {
							Exams.LoadExamInfo();
							Alert('Delete thành công');
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
	
	Exams.ConfirmCopy = function(id,round_id,test_id){
		var control = Exams.modal_copy.control;
		control.tb_id_copy.val(id);
		control.ddl_exam_type_copy.val('0');
		control.ddl_class_copy.val(Exams.ddl_class_id.val());
		control.tb_round_copy.val(round_id);
		control.tb_test_copy.val(test_id);
		control.modal_message.text('');
		Exams.modal_copy.modal('show');
	};
	
	Exams.Copy = function(){
		var control = Exams.modal_copy.control;
		var id = control.tb_id_copy.val();
		var type_id = control.ddl_exam_type_copy.val();
		var class_id = control.ddl_class_copy.val();
		var round_id = control.tb_round_copy.val();
		var test = control.tb_test_copy.val();
		if(type_id!='0'){
			$.ajax({
				url: "/ajax/exam_cmd.php",
				type:"POST",
				dataType: "json",
				data:{action:'exists',id:id,type_id:type_id,class_id:class_id,round_id:round_id,test:test},
				success: function(data) {
					if (data.error == 0) {
						if(data.exists){
							if(data.id==id){
								control.modal_message.text('Không thể copy chính nó');
								Alert('Không thể copy chính nó');
							}
							else{
								control.modal_message.text('bài thi đã tồn tại').show();
								Confirm('Bài này đã tồn tại, bạn muốn ghi đè không?',function(result){
									if(result){
										exe_copy(data.id);
									}
								});
							}
						}
						else{
							exe_copy(0);
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
		}
		else{
			Alert('Hãy chọn môn',function(){
				control.ddl_exam_type_copy.focus();
			});
		}
		
		function exe_copy(exists_id){
			$.ajax({
				url: "/ajax/exam_cmd.php",
				type:"POST",
				dataType: "json",
				data:{action:'copy',id:id,exists_id:exists_id,type_id:type_id,class_id:class_id,round_id:round_id,test:test},
				success: function(data) {
					if (data.error == 0) {
						Exams.LoadExamInfo();
						control.modal_message.text('Copy thành công').show();
						setTimeout(function(){
							Exams.modal_copy.modal('hide');
						},1000);
					} else {
						control.modal_message.text(data.message).show();
						if (data.error == 5) {
							window.location.href='/login.php';
						}
					}
				}
			});
		}
	};

	Exams.ConfirmDownload = function(game_id, id, round_id, test){
		var type = 'xlsx';
		function s2ab(s) {
			if(typeof ArrayBuffer !== 'undefined') {
				var buf = new ArrayBuffer(s.length);
				var view = new Uint8Array(buf);
				for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
				return buf;
			} else {
				var buf = new Array(s.length);
				for (var i=0; i!=s.length; ++i) buf[i] = s.charCodeAt(i) & 0xFF;
				return buf;
			}
		}

		function loadInfo(id, callback){
			$.ajax({
				url: "/ajax/exam_cmd.php",
				type:"POST",
				dataType: "json",
				data:{action:'info',id:id},
				success: function(res) {
					callback(res);
				}
			});
		}

		if(game_id==0){
			loadInfo(id, function(res){
				if(res.error==0){
					var content = res.info.content;
					content = content.reduce(function(arr, q, i){
						var obj = {
							stt: (i+1),
							type: q.type,
							cauhoi: q.question
						};
						if(q.type==1){
							for(var j=0; j<4; j++){
								obj['dapan' + (j + 1)] = q.answer[j];
							}
							obj.traloi = util.parseInt(res.info.answers[i]) + 1;
						}
						else{
							obj.traloi = res.info.answers[i];
						}
						obj.goiy = q.the_answer? q.the_answer: '';
						arr.push(obj);
						return arr;
					},[]);
					var type_id = Exams.ddl_exam_type.val();
					var class_id = Exams.ddl_class_id.val();
					var filename = 'webgame-type_' + type_id + '-lop_' + class_id + '-vong_' + round_id + '-bai_' + test;
					var wb = {
						SheetNames:['Sheet1'],
						Sheets: {
							Sheet1:XLSX.utils.json_to_sheet(content)
						}
					};
					var wbout = XLSX.write(wb, {bookType:type, bookSST:true, type: 'binary'});
					try {
						saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), filename + '.' + type);
					} catch(e) {
						if(typeof console != 'undefined') console.log(e, wbout);
					}
				}
				else{
					Alert(res.message);
				}
			});
		}
		else{
			Alert('Tạm thời tính năng này mới chỉ hoạt động cho webgame');
		}
	};
	//load data
	Exams.LoadInfo();
});