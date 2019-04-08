function ChuotTools(div,min_item,finder,callback){
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

ChuotTools.prototype.Init = function(callback){
	var div_content = $(this.div).html('');
	this.controls={};
	for(var i=0;i<4;i++){
		var sNoisy = i==3?1:0;
		var sInputCategory = i==3?'<input class="form-control input-category" type="text" value="Nhiễu" disabled/>':'<input class="form-control input-category" type="text" value="" placeholder="Tên chủ đề"/>';
		var question_item = $('<div tn-data-noisy="' + sNoisy + '" class="panel panel-info question-category"><div class="panel-heading"><div class="form-group"><label for="tb_total_round" class="col-sm-2 control-label">Tên chủ đề:</label><div class="col-sm-4">' + sInputCategory + '</div><label for="tb_total_round" class="col-sm-1 control-label">Số câu:</label><div class="col-sm-1"><select class="form-control select-number"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option></select></div><div class="col-sm-1"><button type="button" tn-data-index="' + i + '" class="btn btn-primary btn-add">Add</button></div></div></div><div class="panel-body"></div></div>');
		div_content.append(question_item);
		this.controls[i]={
			parent:question_item,
			input_category: question_item.find('.input-category'),
			ddl_select_number: question_item.find('.select-number'),
			bt_add: question_item.find('.btn-add'),
			panel_body: question_item.find('.panel-body')
		};
	}
	
	if(callback && typeof callback === "function") callback();
};

ChuotTools.prototype.SetEvent = function(callback){
	var self = this;
	for(var i=0;i<4;i++){
		var question_item = self.controls[i];
		question_item.bt_add.click(function(){
			var index = $(this).attr('tn-data-index');
			self.AddItem(index, null, function(){
				self._count++;
			});
		});
	}
	if(callback && typeof callback === "function") callback();
};

ChuotTools.prototype.Count = function(){
	return this._count;
};

ChuotTools.prototype.Validate = function(){
	var self = this;
	var result = {};
	var msg = '';
	
	$(this.div + ' input[type="text"]').is(function(){
		var tb = $(this);
		tb.val(tb.val().trim());
	});
	
	var length = $(this.div + ' .question-category[tn-data-noisy="0"] .question-item').length;
	if(length<this._min_item){
		msg += 'Số lượng câu hỏi mới được ' + length + '/' + self._min_item + ' câu.';
	}
	else{
		var list_select_number = $(this.div + ' .question-category[tn-data-noisy="0"] select.select-number');
		if((length = list_select_number.length)==3){
			var sum_select = 0;
			for(var i=0;i<length;i++){
				sum_select += parseInt($(list_select_number[i]).val());
			}
			if(sum_select!=10){
				msg += 'Tổng số lượng "số câu" hỏi phải bằng 10 (không tính câu nhiễu)';
			}
			else{
				var list_question_category = $(this.div + ' .question-category');
				length = list_question_category.length;
				if(length==4){
					var bBreak=false;
					for(var i=0;i<length;i++){
						var input_category = list_question_category.find('.panel-heading input[type="text"].input-category');
						if(input_category.val()==''){
							msg += 'Hãy nhập tên chủ đề';
							input_category.focus();
							break;
						}
						else{
							var question_category = $(list_question_category[i]);
							var ddl_select_number = question_category.find('.panel-heading select.select-number');
							var select_number = ddl_select_number.val();
							
							var list_items = question_category.find('.panel-body .question-item input[type="text"].question');
							var length_item = list_items.length;
							if(select_number>length_item){
								input_category = question_category.find('input[type="text"].input-category');
								msg += 'Chủ đề "' + input_category.val() + '" chưa đủ số lượng câu';
								input_category.focus();
								break;
							}
							else{
								for(var j=0;j<length_item;j++){
									var tb_input = $(list_items[j]);
									if(tb_input.val()==''){
										msg += 'Hãy nhập dữ liệu';
										tb_input.focus();
										bBreak = true;
										break;
									}
								}
							}
							if(bBreak) break;
						}
					}
				}
				else msg += 'xxx';
			}
		}
		else msg += 'xxx';
	}
	
	result.message = msg;
	result.error = (msg==''?0:100);
	return result;
};

ChuotTools.prototype.SetData = function(datas,callback){
	var self = this;
	if(datas && datas.content && datas.content.length>0){
		var length = datas.content.length;
		if(length==4){
			for(var i=0; i<4; i++){
				var data_item = datas.content[i];
				var item_category = self.controls[i];
				item_category.input_category.val(data_item.name);
				item_category.ddl_select_number.val(data_item.number);
				
				var data_answer = datas.answers[i];
				if(data_answer && (len = data_answer.length)>0){
					for(var j=0; j<len; j++){
						this.AddItem(i,data_answer[j],function(){
							self._count++;
						});
					}
				}
			}
		}
	}
	if(callback && typeof callback === "function") callback();
};

ChuotTools.prototype.GetData = function(){
	var result = {};
	var list_question_category = $(this.div + ' .question-category');
	if(list_question_category && (length = list_question_category.length)>0){
		var arrcontent = [];
		var arranswers = [];
		for(var i=0;i<length;i++){
			var content_item={};
			var content_answer=[];
			var question_category = $(list_question_category[i]);
			content_item.number = question_category.find('.panel-heading select.select-number').val();
			content_item.name = question_category.find('input[type="text"].input-category').val();
			content_item.is_noisy = question_category.attr('tn-data-noisy')==1;
			arrcontent.push(content_item);
			
			var list_question_items = question_category.find('.panel-body .question-item');
			var length_item = list_question_items.length;
			if(length_item>0){
				for(var j=0;j<length_item;j++){
					var answer_item={};
					answer_item.content = $(list_question_items[j]).find('input[type="text"].question').val();
					answer_item.type = $(list_question_items[j]).find('select.select-type').val();
					content_answer.push(answer_item);
				}
			}
			arranswers.push(content_answer);
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

ChuotTools.prototype.AddItem = function(index,item_data,callback){
	var self = this;
	var item = $('<div class="form-group question-item"><label for="tb_total_round" class="col-sm-2 control-label">Dạng dữ liệu:</label><div class="col-sm-2"><select class="form-control select-type"><option value="text">Text</option><option value="image">Image</option><option value="latex">Latex</option></select></div><div class="col-sm-4"><input class="form-control question" type="text" value="" placeholder="Giá trị"/></div><div class="col-sm-1"><button type="button" tn-action="add" class="btn btn-danger form-control btn-delete">Delete</button></div><div class="col-sm-1"><button type="button" class="btn btn-info form-control btn-browser" disabled>Chọn ảnh</button></div><div class="col-sm-1"><button type="button" class="btn btn-info form-control btn-latex" disabled>Latex</button></div></div>');
	self.controls[index].panel_body.append(item);
	var ddl_select_type = item.find('.select-type');
	var bt_delete = item.find('.btn-delete');
	var bt_browser = item.find('.btn-browser');
	var bt_latex = item.find('.btn-latex');
	var tb = item.find('.question');
	
	ddl_select_type.change(function(){
		var s_type = $(this).val();
		if(s_type=='image') bt_browser.prop('disabled',false);
		else bt_browser.prop('disabled',true);
		if(s_type=='image' || s_type=='latex') bt_latex.prop('disabled',false);
		else bt_latex.prop('disabled',true);
	});
	
	bt_delete.click(function(){
		Confirm('Bạn có muốn xóa không?',function(result){
			if(result){
				item.remove();
				self._count--;
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
		self.OpenLatex(tb,ddl_select_type.val(),tb.val());
	});
	
	if(item_data){
		if(item_data.type){
			var ddl_type = item.find('.select-type');
			ddl_type.val(item_data.type);
		}
		if(item_data.content){
			var question = item.find('.question');
			question.val(item_data.content);
		}
	}
	
	setTimeout(function(){
		ddl_select_type.trigger('change');
	}, 20);
	
	if(callback && typeof callback === "function") callback();
};

ChuotTools.prototype.Destroy = function(callback){
	
};

ChuotTools.prototype.OpenLatex = function(obj,latex_type,val){
	this.latex_editer = obj;
	this.latex_type = latex_type;
	OpenLatexEditor('tb_abc','','vi-vi',false, val || '','full');
};

ChuotTools.prototype.SetLatex = function(latex,url,callback){
	latex = latex.replace(/\\\[/,'').replace(/\\\]/,'');
	if(latex){
		if(this.latex_type=='latex') this.latex_editer.val(latex);
		if(this.latex_type=='image') this.latex_editer.val('https://latex.codecogs.com/gif.latex?' + latex);
	}
	if(callback && typeof callback === "function") callback();
};