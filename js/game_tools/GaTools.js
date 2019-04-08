/**
 * Created by tanmv on 29/12/2016.
 */
function GaTools(div,min_item,finder,callback){
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

GaTools.prototype.Init = function(callback){
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

GaTools.prototype.SetEvent = function(callback){
	var self = this;
	self.controls.bt_add.click(function(){
		self.AddItem(null, function(){
			self._count++;
			self.controls.lb_question_count.text(self._count);
		});
	});
	if(callback && typeof callback === "function") callback();
};

GaTools.prototype.Count = function(){
	return this._count;
};

GaTools.prototype.Validate = function(){
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
		for(var i = 0; i < length; i++) {
			var question = $(list_questions[i]);
			var tb_answer = question.find('.input-answer');
			if(tb_answer.val() == ''){
				msg += 'Chưa nhập "Đáp án đúng"';
				break;
			}

			var bBreak = false;
			var item_input = question.find('input[type="text"]');
			if(item_input.val() === ''){
				msg +='Chưa nhập nội dung';
				item_input.focus();
				break;
			}
			if(bBreak) break;
		}
	}

	result.message = msg;
	result.error = (msg==''?0:100);
	return result;
};

GaTools.prototype.SetData = function(datas, callback){
	var self = this;
	if(datas && datas.answers && datas.answers.length > 0){
		var length = datas.answers.length;
		for(var i = 0; i < length; i++){
			var data = {
				content: datas.answers[i].content,
				type: datas.answers[i].type
			};
			self.AddItem(data, function(){
				self._count++;
			});
		}
		self.controls.lb_question_count.text(self._count);
	}
	if(callback && typeof callback === "function") callback();
};

GaTools.prototype.GetData = function(){
	var result = {};
	var list_questions = this.controls.questions.find('div.question');
	var length = list_questions.length;
	if(length > 0) {
		var answers = [];
		for(var i=0;i<length;i++){
			var question = $(list_questions[i]);
			var item_input = question.find('input[type="text"]').val();
			var item_type = question.find('select.select-type').val();

			answers.push({
				type: item_type,
				content: item_input
			});
		}
		result.data={
			answers: answers
		};
	}
	result.error = 0;
	result.message = 'ok';
	return result;
};

GaTools.prototype.AddItem = function(item_data, callback){
	var self = this;
	var item = $('<div class="form-group question"><label for="tb_total_round" class="col-sm-2 control-label">Dạng dữ liệu:</label><div class="col-sm-2"><select class="form-control select-type"><option value="text">Text</option><option value="image">Image</option><option value="latex">Latex</option></select></div><div class="col-sm-4"><input class="form-control input-item" type="text" value="" placeholder="Giá trị"></div><div class="col-sm-1"><button type="button" tn-action="add" class="btn btn-danger form-control btn-delete">Xóa</button></div><div class="col-sm-1"><button type="button" class="btn btn-info form-control btn-browser" disabled>Chọn ảnh</button></div><div class="col-sm-1"><button type="button" class="btn btn-info form-control btn-latex" disabled>Latex</button></div></div>');
	self.controls.questions.append(item);
	var bt_delete = item.find('.btn-delete');
	var ddl_select_type = item.find('.select-type');
	var bt_browser = item.find('.btn-browser');
	var bt_latex = item.find('.btn-latex');
	var tb = item.find('.input-item');

	ddl_select_type.change(function(){
		var s_type = $(this).val();
		if(s_type=='image') bt_browser.prop('disabled',false);
		else bt_browser.prop('disabled',true);
		if(s_type=='image' || s_type=='latex') bt_latex.prop('disabled',false);
		else bt_latex.prop('disabled',true);
	});

	bt_delete.click(function(){
		Confirm('Bạn có muốn xóa câu này không?',function(result){
			if(result){
				item.remove();
				self._count--;
				self.controls.lb_question_count.text(self._count);
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

	if(item_data){
		if(item_data.content) tb.val(item_data.content);
		if(item_data.type){
			ddl_select_type.val(item_data.type);
		}
	}
	else{
		tb.focus();
	}

	setTimeout(function(){
		ddl_select_type.trigger('change');
	}, 20);

	if(callback && typeof callback === "function") callback();
};

GaTools.prototype.Destroy = function(callback){

};

GaTools.prototype.OpenLatex = function(obj,latex_type,val){
	this.latex_editer = obj;
	this.latex_type = latex_type;
	OpenLatexEditor('tb_abc','','vi-vi',false, val || '','full');
};

GaTools.prototype.SetLatex = function(latex,url,callback){
	latex = latex.replace(/\\\[/,'').replace(/\\\]/,'');
	if(latex){
		if(this.latex_type=='latex') this.latex_editer.val(latex);
		if(this.latex_type=='image') this.latex_editer.val('https://latex.codecogs.com/gif.latex?' + latex);
	}
	if(callback && typeof callback === "function") callback();
};