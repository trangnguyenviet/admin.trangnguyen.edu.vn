function WEBTOOLS(div,min_item,finder,callback){
	var self = this;
	this.div = div;
	this._count=0;
	this._min_item=min_item;
	this.finder=finder;
	self.Init(function(){
		self.SetEvent(function(){
			callback(self);
		});
	});
}

WEBTOOLS.prototype.Init = function(callback){
	callback();
};

WEBTOOLS.prototype.SetEvent = function(callback){
	callback();
};

WEBTOOLS.prototype.Count = function(){
	return this._count;
};

WEBTOOLS.prototype.Validate = function(){
	var result = {};
	result.error = 0;
	result.message = 'ok';
	return result;
};

WEBTOOLS.prototype.SetData = function(data,callback){
	var result = {};
	result.error = 0;
	result.message = 'ok';
	return result;
};

WEBTOOLS.prototype.GetData = function(callback){
	var result = {};
	result.error = 0;
	result.message = 'ok';
	return result;
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