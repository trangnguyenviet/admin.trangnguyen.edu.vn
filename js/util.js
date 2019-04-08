var util = {};
util.isOnlyNumber = function(s){
	var pattern = /^\d$/;
	return pattern.test(s);  // returns a boolean
};

util.isNumber = function(s){
	var pattern = /^(\-)?\d+(\.\d+)?$/;
	return pattern.test(s);  // returns a boolean
};

util.isInt = function(s){
	var pattern = /^(\-)?\d+$/;
	return pattern.test(s);  // returns a boolean
};

util.isPhoneNumber = function(s){
	var pattern = /^0(9\d{8}|1\d{9})$/;
	return pattern.test(s);  // returns a boolean
};

util.isEmail = function(s){
	var pattern =/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
	return pattern.test(s);  // returns a boolean
};

util.isUsername = function(s){
	var pattern = /^[a-z][a-z0-9_]{5,29}$/;
	return pattern.test(s);  // returns a boolean
};

util.isPassword = function(s){
	var pattern = /^.{6,30}$/;
	return pattern.test(s);  // returns a boolean
};

util.isNameVi = function(s){
	var pattern = /[a-zA-Z áàảãạăâắằấầặẵẫậéèẻẽẹêếềểễệóòỏõọôốồổỗộơớờởỡợíìỉĩịđùúủũụưứửữựÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼÊỀỂỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỨỪỬỮỰỲỴÝỶỸửữựỵỷỹ]{2,30}/;
	return pattern.test(s);
};

// Validates that the input string is a valid date formatted as "dd/mm/yyyy"
util.isValidDate = function(s){
	// First check for the pattern
	if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(s))
		return false;

	// Parse the date parts to integers
	var parts = s.split("/");
	var day = parseInt(parts[0], 10);
	var month = parseInt(parts[1], 10);
	var year = parseInt(parts[2], 10);

	// Check the ranges of month and year
	if(year < 1000 || year > 3000 || month == 0 || month > 12)
		return false;

	var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

	// Adjust for leap years
	if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
		monthLength[1] = 29;

	// Check the range of the day
	return day > 0 && day <= monthLength[month - 1];
};

// Validates that the input string is a valid date formatted as "yyyy-mm-dd"
util.isValidDate2 = function(s){
	// First check for the pattern
	if(!/^\d{4}\-\d{2}\-\d{2}$/.test(s))
		return false;

	// Parse the date parts to integers
	var parts = s.split("-");
	var year = parseInt(parts[0], 10);
	var month = parseInt(parts[1], 10);
	var day = parseInt(parts[2], 10);

	// Check the ranges of month and year
	if(year < 1000 || year > 3000 || month == 0 || month > 12)
		return false;

	var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

	// Adjust for leap years
	if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
		monthLength[1] = 29;

	// Check the range of the day
	return day > 0 && day <= monthLength[month - 1];
};

util.replaceHtml = function(s){
	if(s) return s.replace(/>/g, "&gt;").replace(/</g, "&lt;");
	return "";
};

util.StringFormat = function(s,arg){
	if(s && arg && arg.length && arg.length > 0 && (typeof s === 'string')){
		for(var i=0; i < arg.length; i++) {
			s = s.replace('{' + i + '}', arg[i]);
		}
	}
	return s;
};

util.DateShow = function(s){
	if(s && arg && arg.length && arg.length > 0 && (typeof s === 'string')){
		for(var i=0; i < arg.length; i++) {
			s = s.replace('{' + i + '}', arg[i]);
		}
	}
	return s;
};

//*********************parse data***********************//
util.parseInt = function(s,defaul){
	var pattern = /^(\-)?\d+(\.\d+)?$/;
	if(pattern.test(s)) return parseInt(s);
	else if(defaul) return defaul;
	return 0;
};

util.parseNumber = function(s,defaul){
	var pattern = /^(\-)?\d+(\.\d+)?$/;
	if(pattern.test(s)) return parseFloat(s);
	else if(defaul)return defaul;
	return 0
};

util.parseJson = function(s){
	try{
		return JSON.parse(s);
	}
	catch(e){
		return null;
	}
};

util.toString = function(obj,defaul){
	if(obj){
		return String(obj);
	} else {
		if(defaul) return defaul;
		return "";
	}
};

//date format dd/mm/yyyy
util.parseDate = function(s,defaul){
	if(this.isValidDate(s)){
		var parts = s.split("/");
		var day = parseInt(parts[0], 10);
		var month = parseInt(parts[1], 10);
		var year = parseInt(parts[2], 10);

		return new Date(year,month,day);
	}
	if(defaul)return defaul;
	return null;
};

//date show format dd/mm/yyyy
util.dateShow = function(date){
	if(date){
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		if(month<10) month ='0' + month;
		if(day<10) day='0'+day;
		return day + '/' + month + '/' + year;
	}
	return '';
};

//yyyy-MM-dd HH:mm:ss
util.date2String = function(date){
	if(date){
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var hour = date.getHours();
		var minutes = date.getMinutes();
		var second = date.getSeconds();
		if(month<10) month ='0' + month;
		if(day<10) day='0'+day;
		if(hour<10) hour='0'+hour;
		if(minutes<10) minutes='0'+minutes;
		if(second<10) second='0'+second;
		return  year + '-' + month + '-' + day +' ' + hour + ':' + minutes + ':' + second;
	}
	return '';
};

//yyyyMMddHHmmss
util.date2String2 = function(date){
	if(date){
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var hour = date.getHours();
		var minutes = date.getMinutes();
		var second = date.getSeconds();
		if(month<10) month ='0' + month;
		if(day<10) day='0'+day;
		if(hour<10) hour='0'+hour;
		if(minutes<10) minutes='0'+minutes;
		if(second<10) second='0'+second;
		return  year + '' + month + '' + day +'' + hour + '' + minutes + '' + second;
	}
	return '';
};

//yyyy-MM-dd HH:mm:ss
util.date2String3 = function(date){
	if(date){
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		// var hour = date.getHours();
		// var minutes = date.getMinutes();
		// var second = date.getSeconds();
		if(month<10) month ='0' + month;
		if(day<10) day='0'+day;
		// if(hour<10) hour='0'+hour;
		// if(minutes<10) minutes='0'+minutes;
		// if(second<10) second='0'+second;
		//return  year + '-' + month + '-' + day +' ' + hour + ':' + minutes + ':' + second;
		return  day + '/' + month + '/' + year;
	}
	return '';
};

util.Second2Minute = function(t){
	var minute = Math.floor(t / 60);
	var second = t - (minute * 60);
	if (minute < 10) minute = '0' + minute;
	if (second < 10) second = '0' + second;
	return minute + ':' + second;
};

util.Ms2Date = function(ms){
	if(ms){
		var date = new Date(ms);
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		if(month<10) month = '0' + month;
		if(day<10) day = '0' + day;
		return  year + '-' + month + '-' + day;
	}
	return '';
};

util.Ms2DateTime = function(ms) {
	if(ms) {
		var date = new Date(ms);
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var hour = date.getHours();
		var minutes = date.getMinutes();
		var second = date.getSeconds();
		if(month<10) month = '0' + month;
		if(day<10) day = '0' + day;
		if(hour<10) hour='0'+hour;
		if(minutes<10) minutes='0'+minutes;
		if(second<10) second='0'+second;
		return  year + '-' + month + '-' + day +' ' + hour + ':' + minutes + ':' + second;
	}
	return '';
};

util.randomString = function(n){
	var patent = '0123456789abcdefghijklmnopqrstuvwxyz';
	var patent_length = patent.length;
	var s = '';
	for(i=0;i<n;i++){
		s+=patent[Math.round(Math.random()*patent_length)];
	}
	return s;
};

util.post= function(path, params, method) {
	method = method || "post";
	var form = document.createElement("form");
	form.setAttribute("method", method);
	form.setAttribute("action", path);
	form.style.display = "none";
	if(params && Object.keys(params).length>0){
		for(var key in params) {
			if(params.hasOwnProperty(key)) {
				var hiddenField = document.createElement("input");
				hiddenField.setAttribute("type", "hidden");
				hiddenField.setAttribute("name", key);
				hiddenField.setAttribute("value", params[key]);

				form.appendChild(hiddenField);
			}
		}
	}

	document.body.appendChild(form);
	form.submit();
};

util.hidenMobi = function(mobi) {
	if(mobi && typeof mobi === 'string') {
		var leng = mobi.length;
		if( leng === 10 ) {
			return mobi.substring(0, 3) + '****' + mobi.substring(leng - 3, leng);
		}
	}
	return '';
};