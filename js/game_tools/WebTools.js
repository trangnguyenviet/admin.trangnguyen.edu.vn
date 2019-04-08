function WEBTOOLS(div,min_item,finder,callback){
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

WEBTOOLS.prototype.Init = function(callback){
	var div_content = $(this.div).html('');
	div_content.append('<div class="questions"></div><div class="form-group"><label for="tb_total_round" class="col-sm-3 control-label">Chọn dạng câu hỏi:</label><div class="col-sm-3"><select class="form-control select-question-type"><option value="1">Trắc nghiệm</option><option value="2">Điền đáp án</option></select></div><div class="clearfix visible-xs"></div><div class="col-sm-2"><button type="button" class="btn btn-primary btn-add">Thêm câu hỏi</button></div><div class="col-sm-2"> (<strong class="tn-question-count">0</strong> câu hỏi)</div></div>');
	this.controls = {
		ddl_select_question: div_content.find('.select-question-type'),
		bt_add: div_content.find('.btn-add'),
		questions_content: div_content.find('.questions'),
		span_count: div_content.find('.tn-question-count')
	};
	
	if(callback && typeof callback === "function") callback();
};

WEBTOOLS.prototype.SetEvent = function(callback){
	var self = this;
	this.controls.bt_add.click(function(){
		var question_type = self.controls.ddl_select_question.val();
		self.AddItem(question_type,null,function(){
			self._count++;
			self.controls.span_count.text(self._count);
		});
	});
	if(callback && typeof callback === "function") callback();
};

WEBTOOLS.prototype.Count = function(){
	return this._count;
};

WEBTOOLS.prototype.Validate = function(){
	var result = {};
	var msg = '';
	
	this.controls.questions_content.find('input[type="text"]').is(function(){
		var tb = $(this);
		tb.val(tb.val().trim());
	});
	
	var list_questions = this.controls.questions_content.find('.questions-item');
	if(list_questions && (length = list_questions.length)>0){
		if(length<this._min_item){
			msg += 'Số lượng câu hỏi mới được ' + length + '/' + this._min_item + ' câu.';
		}
		else{
			for(var i=0;i<length;i++){
				var question_item = $(list_questions[i]);
				var question_type = question_item.attr('tn-data-type');
				var question = question_item.find('input[type="text"].question');
				var sQuestion = question.val();
				if(sQuestion==''){
					msg += 'Chưa nhập nội dung câu hỏi';
					question.focus();
					break;
				}
				if(question_type==1){
					var bOk = true;
					for(var j=0;j<4;j++){
						var answer_input = question_item.find('input[type="text"].answer-input-' + j);
						if(answer_input.val()==''){
							msg += 'Chưa nhập nội dung trả lời';
							answer_input.focus();
							bOk = false;
							break;
						}
					}
					if(!bOk) break;
					var answer = question_item.find('input[type="radio"]:checked');
					if(answer && answer.length==0){
						msg += 'Chưa chọn đáp án đúng';
						question.focus();
						break;
					}
					//else{
					//	console.log(answer.val());
					//}
				}
				else if(question_type==2){
					var match = sQuestion.match(/{}/g);
					if(match){
						if(match.length==1){
							//ok
						}
						else{
							msg += 'chỉ được phép có 1 ô nhập đáp án ở dạng {}';
							question.focus();
							break;
						}
					}
					else{
						msg += 'Chưa nhập vùng nhập đáp án - {}';
						question.focus();
						break;
					}
					
					var answer = question_item.find('input[type="text"].answer');
					if(answer.val()==''){
						msg += 'Chưa nhập câu trả lời';
						answer.focus();
						break;
					}
				}
			}
		}
	}
	result.message = msg;
	result.error = (msg==''?0:100);
	return result;
};

WEBTOOLS.prototype.SetData = function(datas,callback){
	var self = this;
	if(datas && datas.content && datas.content.length>0){
		var length = datas.content.length;
		for(var i=0; i<length; i++){
			var items = datas.content[i];
			items.ok = datas.answers[i];
			this.AddItem(items.type,items,function(){
				self._count++;
			});
		}
		self.controls.span_count.text(self._count);
	}
	if(callback && typeof callback === "function") callback();
};

WEBTOOLS.prototype.GetData = function(){
	var result = {};
	var list_questions = this.controls.questions_content.find('.questions-item');
	if(list_questions && (length = list_questions.length)>0){
		var arrQuestion = [];
		var arrAnswer = [];
		for(var i=0;i<length;i++){
			var item={};
			var question_item = $(list_questions[i]);
			item.type = question_item.attr('tn-data-type');
			item.question = question_item.find('input[type="text"].question').val();
			if(item.type==1){
				item.answer = [];
				for(var j=0;j<4;j++){
					var answer_input = question_item.find('input[type="text"].answer-input-' + j);
					item.answer.push(answer_input.val());
				}
				var answer = question_item.find('input[type="radio"]:checked');
				arrAnswer.push(answer.val());
			}
			else if(item.type==2){
				var answer = question_item.find('input[type="text"].answer');
				arrAnswer.push(answer.val());
			}
			arrQuestion.push(item);
		}
		result.data={
			content: arrQuestion,
			answers: arrAnswer
		};
	}
	result.error = 0;
	result.message = 'ok';
	return result;
};

WEBTOOLS.prototype.AddItem = function(question_type,question_data,callback){
	if(question_type != 1 && question_type != 2) return;
	var self = this;
	var questions_content = self.controls.questions_content;
	var bt_delete;
	var question_item;
	if(question_type==1){
		var name = self.RandomString(10);
		name = 'select_' + name;
		question_item = $('<div class="well well-sm questions-item" tn-data-type="1"><div class="col-xs-10"><div class="form-group"><div class="col-xs-12"><input class="form-control input-sm question" type="text" placeholder="Nội dung câu hỏi" /></div></div><div class="row"><div class="col-xs-6 col-sm-3"><div class="input-group"><span class="input-group-addon"><input class="answer-select-0" value="0" name="' + name + '" type="radio"></span><input class="form-control input-sm answer-input-0" type="text" placeholder="Đáp án 1"/></div></div><div class="col-xs-6 col-sm-3"><div class="input-group"><span class="input-group-addon"><input  class="answer-select-1" value="1" name="' + name + '" type="radio"></span><input class="form-control input-sm answer-input-1" type="text" placeholder="Đáp án 2"/></div></div><div class="clearfix visible-xs"></div><div class="col-xs-6 col-sm-3"><div class="input-group"><span class="input-group-addon"><input  class="answer-select-2" value="2" name="' + name + '" type="radio"></span><input class="form-control input-sm answer-input-2" type="text" placeholder="Đáp án 3"/></div></div><div class="col-xs-6 col-sm-3"><div class="input-group"><span class="input-group-addon"><input  class="answer-select-3" value="3" name="' + name + '" type="radio"></span><input class="form-control input-sm answer-input-3" type="text" placeholder="Đáp án 4"/></div></div></div></div><div class="col-xs-2 col-delete"><a class="btn btn-app bt-delete"><i class="fa fa-remove"></i> Xóa</a></div></div>');
		questions_content.append(question_item);
		var question = question_item.find('input[type="text"].question');
		if(question_data){
			// class="answer-select-0"
			// answer-input-0
			if(question_data.question) question.val(question_data.question);
			if(question_data.answer){
				if(question_data.answer[0] !== undefined){
					var answer_input = question_item.find('input[type="text"].answer-input-0');
					answer_input.val(question_data.answer[0]);
				}
				if(question_data.answer[1] !== undefined){
					var answer_input = question_item.find('input[type="text"].answer-input-1');
					answer_input.val(question_data.answer[1]);
				}
				if(question_data.answer[2] !== undefined){
					var answer_input = question_item.find('input[type="text"].answer-input-2');
					answer_input.val(question_data.answer[2]);
				}
				if(question_data.answer[3] !== undefined){
					var answer_input = question_item.find('input[type="text"].answer-input-3');
					answer_input.val(question_data.answer[3]);
				}
			}
			if(question_data.ok) question_item.find('input[type="radio"].answer-select-' + question_data.ok).prop('checked',true);
		}
		else{
			question.focus();
			$(window).scrollBottom(0);
		}
	}else if(question_type==2){
		question_item = $('<div class="well well-sm questions-item" tn-data-type="2"><div class="col-xs-10"><div class="form-group"><div class="col-xs-12"><input class="form-control input-sm question" type="text" placeholder="Nội dung câu hỏi" /></div></div><div class="form-group"><div class="col-xs-12"><input class="form-control input-sm answer" type="text" placeholder="Đáp án đúng" /></div></div></div><div class="col-xs-2 col-delete"><a class="btn btn-app bt-delete"><i class="fa fa-remove"></i> Xóa</a></div></div>');
		questions_content.append(question_item);
		var question = question_item.find('input[type="text"].question');
		if(question_data){
			if(question_data.question) question.val(question_data.question);
			if(question_data.ok) question_item.find('input[type="text"].answer').val(question_data.ok);
		}
		else{
			question.focus();
			$(window).scrollBottom(0);
		}
	}
	bt_delete = question_item.find('.bt-delete');
	bt_delete.click(function(){
		Confirm('Bạn có muốn xóa câu hỏi này không?',function(result){
			if(result){
				self._count--;
				self.controls.span_count.text(self._count);
				question_item.remove();
			}
		});
	});
	
	if(callback && typeof callback === "function") callback();
};

WEBTOOLS.prototype.RandomString = function(n){
	var patent = '0123456789abcdefghijklmnopqrstuvwxyz';
	var patent_length = patent.length;
	var s = '';
	for(i=0;i<n;i++){
		s+=patent[Math.floor(Math.random()*patent_length)];
	}
	return s;
};

WEBTOOLS.prototype.Destroy = function(callback){
	
};

WEBTOOLS.prototype.OpenLatex = function(obj,latex_type,val){
	this.latex_editer = obj;
	this.latex_type = latex_type;
	OpenLatexEditor('tb_abc','','vi-vi',false, val || '','full');
};

WEBTOOLS.prototype.SetLatex = function(latex,url,callback){
	latex = latex.replace(/\\\[/,'').replace(/\\\]/,'');
	if(latex){
		if(this.latex_type=='latex') this.latex_editer.val(latex);
		if(this.latex_type=='image') this.latex_editer.val('https://latex.codecogs.com/gif.latex?' + latex);
	}
	if(callback && typeof callback === "function") callback();
};