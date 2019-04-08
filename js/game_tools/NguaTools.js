function NguaTools(div,min_item,finder,callback){
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

NguaTools.prototype.Init = function(callback){
	var div_content = $(this.div).html('');
	div_content.append('<div class="questions"></div><div class="form-group"><div class="col-sm-offset-5 col-sm-2 text-center"><button type="button" class="btn btn-primary btn-add">Thêm câu hỏi</button></div><div class="col-sm-2"> (<strong class="tn-question-count">0</strong> câu hỏi)</div></div>');
	this.controls={
		questions: div_content.find('.questions'),
		btn_add: div_content.find('.btn-add'),
		lb_question_count: div_content.find('.tn-question-count')
	};

	if(callback && typeof callback === "function") callback();
};

NguaTools.prototype.SetEvent = function(callback){
	var self = this;
	self.controls.btn_add.click(function(){
		self.AddItem(null, function(){
			self._count++;
			self.controls.lb_question_count.text(self._count);
		});
	});
	if(callback && typeof callback === "function") callback();
};

NguaTools.prototype.Count = function(){
	return this._count;
};

NguaTools.prototype.Validate = function(){
	var self = this;
	var result = {};
	var msg = '';

	self.controls.questions.find('input[type="text"]').is(function(){
		var tb = $(this);
		tb.val(tb.val().trim());
	});

	var list_question_item = self.controls.questions.find('.question-item');
	var length = list_question_item.length;
	if(length>=self._min_item){
		for(var i=0;i<length;i++){
			var question_item = $(list_question_item[i]);
			var question = question_item.find('.question-0');
			if(question.val()==''){
				msg+='Hãy nhập nội dung câu hỏi';
				question.focus();
				break;
			}
			else{
				question = question_item.find('.question-1');
				if(question.val()==''){
					msg+='Hãy nhập nội dung câu hỏi';
					question.focus();
					break;
				}
			}
		}
	}
	else{
		msg+='Số câu hỏi mới được ' + length + '/' + self._min_item + ' câu';
	}

	result.message = msg;
	result.error = (msg==''?0:100);
	return result;
};

NguaTools.prototype.SetData = function(datas,callback){
	var self = this;
	if(datas && datas.content && datas.content.length>0){
		var length = datas.content.length;
		for(var i=0; i<length; i++){
			var data_item = datas.content[i];
			if(data_item && data_item.length>0){
				this.AddItem(data_item,function(){
					self._count++;
				});
			}
		}
		self.controls.lb_question_count.text(self._count);
	}
	if(callback && typeof callback === "function") callback();
};

NguaTools.prototype.GetData = function(){
	var self = this;
	var result = {};
	var list_question_item = self.controls.questions.find('.question-item');
	if(list_question_item && (length = list_question_item.length)>0){
		var arrcontent = [];
		//var arranswers = [];
		for(var i=0;i<length;i++){
			var content_question=[];
			var question_item = $(list_question_item[i]);
			var content_item_0={
				type:question_item.find('select.select-type-0').val(),
				content:question_item.find('input[type="text"].question-0').val()
			};
			var content_item_1={
				type:question_item.find('select.select-type-1').val(),
				content:question_item.find('input[type="text"].question-1').val()
			};
			content_question.push(content_item_0);
			content_question.push(content_item_1);

			arrcontent.push(content_question);
		}
		result.data={
			content: arrcontent,
			answers: []
		};
	}
	result.error = 0;
	result.message = 'ok';
	return result;
};

NguaTools.prototype.AddItem = function(item_data,callback){
	var self = this;
	var item = $('<div class="form-group question-item"><div class="col-sm-5"><div class="input-group"> <div class="input-group-addon"><select class="select-type-0"><option value="text">Text</option><option value="image">Image</option><option value="latex">Latex</option></select></div> <input class="form-control question-0" type="text" value="" placeholder="Giá trị 1"/><span class="input-group-btn"> <button class="btn btn-img-0 btn-secondary" type="button"><i class="fa fa-picture-o" aria-hidden="true"></i></button></span></div></div><label class="col-sm-1 text-center">&lt;-&gt;</label><div class="col-sm-5"><div class="input-group"> <div class="input-group-addon"><select class="select-type-1"><option value="text">Text</option><option value="image">Image</option><option value="latex">Latex</option></select></div> <input class="form-control question-1" type="text" value="" placeholder="Giá trị 2"/><span class="input-group-btn"> <button class="btn btn-img-1 btn-secondary" type="button"><i class="fa fa-picture-o" aria-hidden="true"></i></button></span></div></div><div class="col-sm-1"><button type="button" tn-action="add" class="btn btn-danger btn-sm form-control btn-delete">Xóa</button></div></div>');
	self.controls.questions.append(item);
	var bt_delete = item.find('.btn-delete');
	var bt_img_0 = item.find('.btn-img-0');
	var bt_img_1 = item.find('.btn-img-1');
	var ddl_type_0 = item.find('.select-type-0');
	var question_0 = item.find('.question-0');
	var ddl_type_1 = item.find('.select-type-1');
	var question_1 = item.find('.question-1');

	bt_delete.click(function(){
		Confirm('Bạn có muốn xóa câu hỏi này không?',function(result){
			if(result){
				item.remove();
				self._count--;
				self.controls.lb_question_count.text(self._count);
			}
		});
	});

	function ShowEditer(ddl,input){
		var type = ddl.val();
		if('image' == type){
			self.finder.selectActionFunction = function(src){
				input.val(src);
			};
			self.finder.popup('/ckfinder/',700,400);
		}
		else if('latex' == type){
			self.OpenLatex(input,type);
		}
		else{
			ShowMessError('không hỗ trợ với ' + type);
		}
	}
	bt_img_0.click(function(){
		ShowEditer(ddl_type_0, question_0);
	});
	bt_img_1.click(function(){
		ShowEditer(ddl_type_1, question_1);
	});

	if(item_data){
		if(data = item_data[0]){
			if(data.type){
				// var ddl_type = item.find('.select-type-0');
				ddl_type_0.val(data.type);
			}
			if(data.content){
				// var question = item.find('.question-0');
				question_0.val(data.content);
			}
		}
		if(data = item_data[1]){
			if(data.type){
				// var ddl_type = item.find('.select-type-1');
				ddl_type_1.val(data.type);
			}
			if(data.content){
				// var question = item.find('.question-1');
				question_1.val(data.content);
			}
		}
	}

	if(callback && typeof callback === "function") callback();
};

NguaTools.prototype.Destroy = function(callback){

};

NguaTools.prototype.OpenLatex = function(obj,latex_type,val){
	this.latex_editer = obj;
	this.latex_type = latex_type;
	OpenLatexEditor('tb_abc','','vi-vi',false, val || '','full');
};

NguaTools.prototype.SetLatex = function(latex,url,callback){
	latex = latex.replace(/\\\[/,'').replace(/\\\]/,'');
	if(latex){
		if(this.latex_type=='latex') this.latex_editer.val(latex);
		if(this.latex_type=='image') this.latex_editer.val('https://latex.codecogs.com/gif.latex?' + latex);
	}
	if(callback && typeof callback === "function") callback();
};