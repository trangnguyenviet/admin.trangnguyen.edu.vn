var ExamEdit;
var GameTool;
$(function(){
	ExamEdit={
		is_exam_info:true,
		div_content:'#content_tools',
		game_id:0,
		tb_id:$('#tb_id'),
		ddl_exam_type:$('#ddl_exam_type'),
		tb_class_id:$('#tb_class_id'),
		tb_round_id:$('#tb_round_id'),
		tb_test:$('#tb_test'),
		//tb_play:$('#tb_play'),
		tb_time:$('#tb_time'),
		ddl_game:$('#ddl_game'),
		bt_save:$('#bt_save'),
		bt_back:$('#bt_back'),
		content_tools:$('#content_tools')
	};
	
	ExamEdit.bt_back.click(function(){
		window.history.go(-1);
	});
	
	if(window.is_exam_info===false){
		ExamEdit.is_exam_info=false;
		Alert('Thông tin không phù hợp!');
		return;
	}
	else{
		if(window.exam_info!==null){
			//fill_data
			var info = window.exam_info;
			ExamEdit.tb_id.val(info._id);
			ExamEdit.ddl_exam_type.val(info.type_id);
			ExamEdit.tb_class_id.val(info.class_id);
			ExamEdit.tb_round_id.val(info.round_id);
			ExamEdit.tb_test.val(info.test);
			ExamEdit.tb_time.val(info.time);
			ExamEdit.ddl_game.val(info.game_id);
			ExamEdit.game_id=info.game_id;
		}
		else{
			ExamEdit.ddl_exam_type.val(window.type_id);
			ExamEdit.tb_class_id.val(window.class_id);
			ExamEdit.tb_round_id.val(window.round_id);
			ExamEdit.tb_test.val(window.test_id);
			ExamEdit.tb_time.val(1200);
			ExamEdit.ddl_game.val(0);
		}
	}
	
	var finder = new CKFinder();
	finder.defaultLanguage = 'vi';
	finder.language = 'vi';
	finder.removePlugins = 'basket';
	//finder.selectActionFunction = function(src){
	//	SetThumb(src);
	//};
	finder.resourceType = 'Image_Game';
	finder.tabIndex = 0;
	//finder.startupPath = "Image Game:/";
	//finder.callback = function(api){
	//	api.openFolder('Thumb_News', '/');
	//};
	
	//init data
	ExamEdit.InitTools = function(game_id,div,callback){
		if(game_id==0){
			GameTool=null;
			new WEBTOOLS(div,10,finder,callback);
		}
		else if(game_id==1){
			GameTool=null;
			new ChuotTools(div,10,finder,callback);
		}
		else if(game_id==2){
			GameTool=null;
			//Alert('Chưa hỗ trợ game này');
			new TrauTools(div,10,finder,callback);
		}
		else if(game_id==3){
			GameTool=null;
			//Alert('Chưa hỗ trợ game này');
			new HoTools(div,10,finder,callback);
		}
		else if(game_id==4){
			GameTool=null;
			new MeoTools(div,10,finder,callback);
		}
		else if(game_id==5){
			GameTool=null;
			Alert('Chưa hỗ trợ game này');
		}
		else if(game_id==6){
			GameTool=null;
			Alert('Chưa hỗ trợ game này');
		}
		else if(game_id==7){
			GameTool=null;
			// Alert('Chưa hỗ trợ game này');
			new NguaTools(div,10,finder,callback);
		}
		else if(game_id==8){
			GameTool=null;
			Alert('Chưa hỗ trợ game này');
		}
		else if(game_id==9){
			GameTool=null;
			Alert('Chưa hỗ trợ game này');
		}
		else if(game_id==10){
			GameTool=null;
			//Alert('Chưa hỗ trợ game này');
			new GaTools(div,10,finder,callback);
		}
		else if(game_id==11){
			GameTool=null;
			Alert('Chưa hỗ trợ game này');
		}
		else if(game_id==12){
			GameTool=null;
			Alert('Chưa hỗ trợ game này');
		}
		else{
			GameTool=null;
			Alert('Game này không tồn tại');
		}
	};
	ExamEdit.InitTools(ExamEdit.game_id,ExamEdit.div_content,function(game_tools){
		GameTool = game_tools;
		GameTool.SetData(window.exam_info);
	});
	//end init data
	
	//event
	ExamEdit.ddl_game.change(function(){
		var game_id = ExamEdit.ddl_game.val();
		if(GameTool){
			var exam_count = GameTool.Count();
			if(exam_count>0){
				Confirm('Chuyển sang game mới, sẽ xóa hết dữ liệu hiện có<br/>bạn có chắc chắn không?',function(result){
					if(result){
						ChangeGameType(game_id);
					}
					else{
						setTimeout(function(){
							ExamEdit.ddl_game.val(ExamEdit.game_id);
						}, 100);
					}
				});
			}
			else{
				ChangeGameType(game_id);
			}
		}
		else{
			ChangeGameType(game_id);
		}
		
		function ChangeGameType(game_id){
			$(ExamEdit.div_content).html('');
			ExamEdit.game_id = game_id;
			ExamEdit.InitTools(game_id,ExamEdit.div_content,function(game_tools){
				GameTool = game_tools;
			});
		}
	});
	ExamEdit.bt_save.click(function(){
		if(GameTool){
			var validate_info = GameTool.Validate();
			//console.log('validate_info',validate_info);
			if(validate_info.error!=0){
				ShowMessError(validate_info.message);
			}
			else{
				var data_info = GameTool.GetData();
				if(validate_info.error==0){
					ExamEdit.Save(data_info);
				}
				//console.log('data_info',data_info);
			}
		}
		else{
			Alert('Không hỗ trợ game này hoặc đang phát triển!');
		}
	});
	
	ExamEdit.Save=function(data_info){
		var data = data_info.data;
		data.id = ExamEdit.tb_id.val(),
		data.type_id = ExamEdit.ddl_exam_type.val(),
		data.class_id = ExamEdit.tb_class_id.val(),
		data.round_id = ExamEdit.tb_round_id.val(),
		data.test = ExamEdit.tb_test.val(),
		data.time = ExamEdit.tb_time.val(),
		data.game_id = ExamEdit.ddl_game.val();
		data.action='save';
		//console.log(data);
		//return;
		$.ajax({
			url: "/ajax/exam_cmd.php",
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