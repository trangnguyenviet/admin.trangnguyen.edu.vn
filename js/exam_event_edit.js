var ExamEdit;
var GameTool;
$(function(){
	ExamEdit={
		div_content:'#content_tools',
		game_id:0,
		tb_class_id: $('#tb_class_id'),
		tb_id:$('#tb_id'),
		tb_type_id: $('#tb_type_id'),
		tb_play: $('#tb_play'),
		tb_time:$('#tb_time'),
		tb_spq:$('#tb_spq'),
		bt_save:$('#bt_save'),
		bt_back: $('#bt_back'),
		content_tools:$('#content_tools')
	};

	var query_string = Urllib.GetQueryString();

	if(exam_info){
		ExamEdit.tb_class_id.val(exam_info.class_id);
		ExamEdit.tb_play.val(exam_info.play);
		ExamEdit.tb_time.val(exam_info.time);
		ExamEdit.tb_spq.val(exam_info.spq?exam_info.spq: 10);
	}
	else{
		ExamEdit.tb_class_id.val(query_string['class_id']);
		ExamEdit.tb_play.val(100);
		ExamEdit.tb_time.val(1800);
		ExamEdit.tb_spq.val(10);
	}

	//init data
	ExamEdit.InitTools = function(game_id,div,callback){
		new WEBTOOLS(div,1,null,callback);
	};
	ExamEdit.InitTools(ExamEdit.game_id,ExamEdit.div_content,function(game_tools){
		GameTool = game_tools;
		GameTool.SetData(exam_info);
	});
	//end init data

	//event
	ExamEdit.bt_back.click(function(){
		window.history.go(-1);
	});
	ExamEdit.bt_save.click(function(){
		if(GameTool){
			var validate_info = GameTool.Validate();
			if(validate_info.error!=0){
				ShowMessError(validate_info.message);
			}
			else{
				var data_info = GameTool.GetData();
				if(validate_info.error==0){
					ExamEdit.Save(data_info);
				}
			}
		}
		else{
			Alert('Không hỗ trợ game này hoặc đang phát triển!');
		}
	});

	ExamEdit.Save=function(data_info){
		var data = data_info.data;
		data.id = ExamEdit.tb_id.val();
		data.type_id = ExamEdit.tb_type_id.val();
		data.class_id = ExamEdit.tb_class_id.val();
		//data.round_id = ExamEdit.tb_round_id.val();
		//data.test = ExamEdit.tb_test.val();
		data.time = ExamEdit.tb_time.val();
		data.play = ExamEdit.tb_play.val();
		data.spq = ExamEdit.tb_spq.val();
		data.action='save';
		//console.log(data);
		//return;
		$.ajax({
			url: "/ajax/exam_event_cmd.php",
			type:'POST',
			dataType: "json",
			data:data,
			success:function(data){
				if(data.error==0){
					ShowMessError('Lưu thành công');
					ExamEdit.tb_id.val(data.id);
				}
				else{
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
		});
	};

	ExamEdit.SetLatex = function(text){
		console.log("latex:",text);
	};
});