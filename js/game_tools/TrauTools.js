function TrauTools(div,min_item,finder,callback){
	var self = this;
	this.div = div;
	this._count=0;
	this._min_item=min_item;
	this.finder=finder;
	self.Init(function(){
		self.SetEvent(function(){
			if(callback && typeof callback === "function") callback(self);
		});
	});
	return this;
}

TrauTools.prototype.Init = function(callback){
	var div_content = $(this.div).html('');
	var div_init = $('<div class="questions"></div><div class="form-group"><div class="col-sm-offset-5 col-sm-2 text-center"><button type="button" class="btn btn-primary btn-add">Thêm câu hỏi</button></div><div class="col-sm-2"> (<strong class="tn-question-count">0</strong> câu hỏi)</div></div>');
	div_content.append(div_init);
	this.controls={
		questions: div_content.find('div.questions'),
		bt_add: div_init.find('.btn-add'),
		lb_question_count: div_init.find('.tn-question-count')
	};
	if(callback && typeof callback === "function") callback();
};

TrauTools.prototype.SetEvent = function(callback){
	var self = this;
	self.controls.bt_add.click(function(){
		self.AddItem(null, function(){
			self._count++;
			self.controls.lb_question_count.text(self._count);
		});
	});
	if(callback && typeof callback === "function") callback();
};

TrauTools.prototype.Count = function(){
	return this._count;
};

TrauTools.prototype.Validate = function(){
	var self = this;
	var result = {};
	var msg = '';
	
	self.controls.questions.find('input[type="text"]').is(function(){
		var tb = $(this);
		tb.val(tb.val().trim());
	});
	
	var list_questions = this.controls.questions.find('div.question');
	var length = list_questions.length;
	if(length<this._min_item){
		msg += 'Số lượng câu hỏi mới được ' + length + '/' + self._min_item + ' câu.';
	}
	else{
		for(var i=0;i<length;i++){
			var question = $(list_questions[i]);
			var tb_answer = question.find('.input-answer');
			if(tb_answer.val()==''){
				msg +='Chưa nhập "Đáp án đúng"';
				break;
			}
			
			var bBreak = false;
			var list_question_item = question.find('.question-item');
			var item_length = list_question_item.length;
			if(item_length>0){
				var iCount_input=0;
				for(var j=0;j<item_length;j++){
					var question_item = $(list_question_item[j]);
					var item_input = question_item.find('input[type="text"]');
					if(item_input.val()==''){
						msg +='Chưa nhập nội dung';
						item_input.focus();
						break;
					}
					else if(item_input.val()=='{}') iCount_input++;
				}
				if(iCount_input==0){
					msg +='Chưa có vùng nhập đáp án đúng';
					tb_answer.focus();
					break;
				}
				else if(iCount_input>1){
					msg +='Với mỗi 1 câu hỏi chỉ có 1 vùng nhập đáp án';
					tb_answer.focus();
					break;
				}
			}
			else{
				msg +='Chưa có nội dung câu hỏi"';
				tb_answer.focus();
				break;
			}
			if(bBreak) break;
		}
	}
	
	result.message = msg;
	result.error = (msg==''?0:100);
	return result;
};

TrauTools.prototype.SetData = function(datas,callback){
	//console.log(datas);
	//return;
	var self = this;
	if(datas && datas.content && datas.content.length>0){
		var length = datas.content.length;
		for(var i=0; i<length; i++){
			var data = {
				ok: datas.answers[i],
				item: datas.content[i]
			};
			self.AddItem(data, function(){
				self._count++;
			});
		}
		self.controls.lb_question_count.text(self._count);
	}
	if(callback && typeof callback === "function") callback();
};

TrauTools.prototype.GetData = function(){
	var result = {};
	var list_questions = this.controls.questions.find('div.question');
	var length = list_questions.length;
	if(length>0){
		var arrcontent = [];
		var arranswers = [];
		for(var i=0;i<length;i++){
			var arrQuestion = [];
			var question = $(list_questions[i]);
			var tb_answer = question.find('.input-answer');
			arranswers.push(tb_answer.val());
			
			var list_question_item = question.find('.question-item');
			var item_length = list_question_item.length;
			if(item_length>0){
				for(var j=0;j<item_length;j++){
					var content_item={};
					var question_item = $(list_question_item[j]);
					content_item.content = item_input = question_item.find('input[type="text"]').val();
					if(content_item.content=='{}'){
						content_item.type='text';
					}
					else content_item.type=question_item.find('select.select-type').val();
					
					arrQuestion.push(content_item);
				}
			}
			arrcontent.push(arrQuestion);
		}
		result.data={
			content: arrcontent,
			answers: arranswers
		};
	}
	result.error = 0;
	result.message = 'ok';
	return result;
};

TrauTools.prototype.AddItem = function(item_data,callback){
	var self = this;
	var item = $('<div class="panel panel-info question"><div class="panel-body"></div><div class="panel-footer"><div class="form-group"><label for="tb_total_round" class="col-sm-2 control-label">Đáp án đúng:</label><div class="col-sm-6"><input class="form-control input-answer" type="text" value="" placeholder="Đáp án đúng"></div><div class="col-sm-1"><button type="button" tn-data-index="0" class="btn btn-success form-control btn-add">Thêm</button></div><div class="col-sm-1"><button type="button" tn-data-index="0" class="btn btn-danger form-control btn-delete">Xóa</button></div></div></div></div>');
	self.controls.questions.append(item);
	var panel_body = item.find('.panel-body');
	var bt_delete = item.find('.btn-delete');
	var bt_add = item.find('.btn-add');
	var input_answer = item.find('.input-answer');
	
	bt_delete.click(function(){
		Confirm('Bạn có muốn xóa câu này không?',function(result){
			if(result){
				item.remove();
				self._count--;
				self.controls.lb_question_count.text(self._count);
			}
		});
	});
	
	bt_add.click(function(){
		AddChildItem(null);
	});
	
	if(item_data){
		if(item_data.ok!==undefined){
			input_answer.val(item_data.ok);
		}
		if(item_data.item && (length = item_data.item.length) > 0){
			for(var i=0;i<length;i++){
				AddChildItem(item_data.item[i]);
			}
		}
	}
	else{
		input_answer.focus();
	}
	if(callback && typeof callback === "function") callback();
	
	function AddChildItem(data){
		var question_item = $('<div class="form-group question-item"><label for="tb_total_round" class="col-sm-2 control-label">Dạng dữ liệu:</label><div class="col-sm-2"><select class="form-control select-type"><option value="text">Text</option><option value="image">Image</option><option value="latex">Latex</option></select></div><div class="col-sm-4"><input class="form-control input-item" type="text" value="" placeholder="Giá trị"></div><div class="col-sm-1"><button type="button" tn-action="add" class="btn btn-danger form-control btn-delete">Xóa</button></div><div class="col-sm-1"><button type="button" class="btn btn-info form-control btn-browser" disabled>Chọn ảnh</button></div><div class="col-sm-1"><button type="button" class="btn btn-info form-control btn-latex" disabled>Latex</button></div></div>');
		panel_body.append(question_item);
		var ddl_select_type = question_item.find('.select-type');
		var bt_delete_item = question_item.find('.btn-delete');
		var bt_browser = question_item.find('.btn-browser');
		var bt_latex = question_item.find('.btn-latex');
		var tb = question_item.find('.input-item');
		
		ddl_select_type.change(function(){
			var s_type = $(this).val();
			if(s_type=='image') bt_browser.prop('disabled',false);
			else bt_browser.prop('disabled',true);
			if(s_type=='image' || s_type=='latex') bt_latex.prop('disabled',false);
			else bt_latex.prop('disabled',true);
		});
		
		bt_delete_item.click(function(){
			Confirm('Bạn có muốn xóa phần tử này không?',function(result){
				if(result){
					question_item.remove();
				}
			});
		});
		
		bt_browser.click(function(){
			var finder = self.finder;
			self.finder.selectActionFunction = function(src){
				tb.val(src);
			};
			finder.popup('/ckfinder/',700,400);
		});
		
		bt_latex.click(function(){
			self.OpenLatex(tb,ddl_select_type.val());
		});
		
		if(data){
			if(data.content) tb.val(data.content);
			if(data.type){
				var ddl = question_item.find('select.select-type');
				ddl.val(data.type);
			}
		}
		else{
			tb.focus();
		}
		
		setTimeout(function(){
			ddl_select_type.trigger('change');
		}, 20);
	}
};

TrauTools.prototype.Destroy = function(callback){
	
};

TrauTools.prototype.OpenLatex = function(obj,latex_type,val){
	this.latex_editer = obj;
	this.latex_type = latex_type;
	OpenLatexEditor('tb_abc','','vi-vi',false, val || '','full');
};

TrauTools.prototype.SetLatex = function(latex,url,callback){
	latex = latex.replace(/\\\[/,'').replace(/\\\]/,'');
	if(latex){
		if(this.latex_type=='latex') this.latex_editer.val(latex);
		if(this.latex_type=='image') this.latex_editer.val('https://latex.codecogs.com/gif.latex?' + latex);
	}
	if(callback && typeof callback === "function") callback();
};