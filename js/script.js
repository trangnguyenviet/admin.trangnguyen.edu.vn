var spinner;
// var popup_user_online;

var Urllib = {
	encode: function(a) {
		return encodeURIComponent(a);
	},
	decode: function(a) {
		return decodeURIComponent(a);
	},
	GetQueryString: function() {
		var a = [],hash;
		var b = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for (var i = 0; i < b.length; i++) {
			hash = b[i].split('=');
			a.push(hash[0]);
			a[hash[0]] = unescape(hash[1]);
		}
		return a;
	},
	getIsMobileClient: function(a) {
		var b = navigator.userAgent.toLowerCase();
		var c = ['android', "windows ce", 'blackberry', 'palm', 'mobile'];
		for (var i = 0; i < c.length; i++) {
			if (b.indexOf(c[i]) > -1) {
				return (a) ? (c[i].toUpperCase() == a.toUpperCase()) : true;
			}
		}
		return false;
	}
};

$(document).ready(function() {
	spinner = $('#spinner');

	// var query_string = Urllib.GetQueryString();
	// if(query_string && query_string.length>0){
	// 	var url = query_string['page'];
	// 	if(!url) {
	// 		//$('.sidebar-menu li:eq(0)').addClass("active");
	// 		$('.sidebar-menu li.home').addClass("active");
	// 		return;
	// 	}
	// 	url = '?page=' + url;
	// 	var active_index = 0;
	// 	$('.sidebar-menu li').is(function(){
	// 		var sefl = $(this);
	// 		var a = sefl.children().first();
	// 		var href = a.attr('href');
	// 		if(href==url){
	// 			sefl.addClass("active");
	// 			//break;
	// 		}
	// 		else{
	// 			var ul = a.next();
	// 			ul.find('a').is(function(){
	// 				href = $(this).attr('href');
	// 				if(href==url){
	// 					sefl.addClass("active");
	// 					//break;
	// 				}
	// 			});
	// 		}
	// 	});
	// }

	var url = window.location.pathname;
	$('.sidebar-menu li').is(function(){
		var sefl = $(this);
		var a = sefl.children().first();
		var href = a.attr('href');
		if(href==url){
			sefl.addClass("active");
			//break;
		}
		else{
			var ul = a.next();
			ul.find('a').is(function(){
				href = $(this).attr('href');
				if(href==url){
					sefl.addClass("active");
					//break;
				}
			});
		}
	});

	$('.logout').click(function(){
		bootbox.confirm({
			message:'Bạn muốn thoát không?',
			size:'small',
			callback:function(result) {
				if(result){
					window.location.href='/logout.php';
				}
			}
		});
		return false;
	});
});

$.ajaxSetup({
	//url: '/ajax',
	type: 'POST',
	cache: false,
	timeout: 30000,
	//async: true,
	//dataType: 'json',
	//data: {param1: 'value1'},
	beforeSend: function(xhr){
		spinner.show();
	},
	/*statusCode:{
	 200:function(){
	 console.log(200);
	 },
	 404:function(){
	 console.log(404);
	 },
	 500:function(){
	 console.log(500);
	 }
	 },*/
	error:function(xhr,status,error){
		console.log(xhr,status,error);
		//if(status!=200)ShowMessError(error);
		ShowMessError(error);
	},
	//success:function(result,status,xhr){
	//	//console.log(result,status,xhr);
	//},
	complete: function(xhr,status){
		//console.log(xhr.status,xhr.statusText,status);
		spinner.hide();
	}
});

function ShowMessError(s){
	var toast = $('.div_msg_error');
	if(s=='') $('.div_msg_error .content').text('Timeout');
	else $('.div_msg_error .content').text(s);
	toast.fadeIn("slow");

	var t = setTime();

	toast.mouseover(function(){
		if(t) clearTimeout(t);
	});

	toast.mouseout(function(){
		t = setTime();
	});

	function setTime(){
		return setTimeout(function(){
			toast.fadeOut("slow");
		},5000);
	}
}

function Alert(msg,callback){
	bootbox.alert({
		message:msg,
		size: 'small',
		callback: function() {
			if(callback && typeof callback == 'function') callback();
		}
	});
}
function Confirm(msg,callback){
	bootbox.confirm({
		message:msg,
		size: 'small',
		callback: function(result) {
			if(callback && typeof callback == 'function') callback(result);
		}
	});
}
function Prompt(title,value,callback){
	bootbox.prompt({
		title: title,
		value: value,
		callback: function(result) {
			if (result === null) callback(null);
			else callback(result);
		}
	});
}
// function setSidebarHeight() {
// 	setTimeout(function() {
// 		var height = $(document).height();
// 		$('.grid_12').each(function() {
// 			height -= $(this).outerHeight();
// 		});
// 		height -= $('#site_info').outerHeight();
// 		height -= 1;
// 		//salert(height);
// 		$('.sidemenu').css('height', height);
// 	}, 100);
// }

$.fn.slideFadeToggle = function(easing, callback) {
	return this.animate({ opacity: 'toggle', height: 'toggle' }, 'fast', easing, callback);
};

function GoScrollTo(id){
	id = id.replace("link", "");
	$('html,body').animate({scrollTop: $(id).offset().top},'slow');
}
function Date2String(timestamp){
	if(!timestamp) return '';
	var date = new Date(timestamp*1000);
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
}

function Date2String2(timestamp){
	if(!timestamp) return '';
	var date = new Date(timestamp*1000);
	if(date){
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		if(month<10) month ='0' + month;
		if(day<10) day='0'+day;
		return  year + '-' + month + '-' + day;
	}
	return '';
}

// function GetUserIdShow(user_id,date_use){
// 	if(txt){
// 		if(txt!=''){
// 			return txt;
// 		}
// 		else{
// 			return '&nbsp';
// 		}
// 	}
// 	else{
// 		return '&nbsp';
// 	}
// }

function GetTextHtml(txt){
	return (txt && txt!='')? txt: '&nbsp';
}

// function GetIP(ip){
// 	ip = GetTextHtml(ip);
// 	if(ip!='nbsp;'){
// 		return '<a href="http://ipinfo.io/' + ip + '" target="_blank">' + ip + '</a>';
// 	}
// 	return ip;
// }
// function Replace_Ip(ip){
// 	if(ip){
// 		return ip.replace(/[.]/ig, '_');
// 	}
// 	return '';
// }

function CheckNumber(number){
	return /^\d+$/g.test(number);
}

function write(str){
	str= str.toLowerCase();
	str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
	str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
	str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
	str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
	str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
	str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
	str= str.replace(/đ/g,"d");
	str= str.replace(/!|@|\$|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\'| |\"|\&|\#|\[|\]|~/g,"-");
	str= str.replace(/-+-/g,"-");
	str= str.replace(/^\-+|\-+$/g,"");
	return str;
}

//GenPageJs(540,100,2,'pagination','active',5,'GoPage')
function GenPageJs($totalrecord,$irecordofpage,$pageindex,$className,$classActive,$rshow,$function_name){
	$numberpage = 0;
	if ($totalrecord % $irecordofpage == 0)
		$numberpage = Math.floor($totalrecord / $irecordofpage);
	else
		$numberpage = Math.floor($totalrecord / $irecordofpage) + 1;

	if ($numberpage == 1)
		return "";

	$loopend = 0;
	$loopstart = 0;
	$istart = false;
	$iend = false;
	if ($pageindex == 0)
	{
		$loopstart = 0;
		$loopend = $numberpage > ($rshow - 1) ? $rshow : $numberpage;
		if ($numberpage > $rshow)
			$iend = true;
	}
	else
	{
		if ($pageindex < $numberpage - ($rshow - 1) && $pageindex != 0)
		{
			$loopstart = $pageindex - 1;
			$loopend = $pageindex + ($rshow - 1);
			$iend = true;
			if ($pageindex > 1)
				$istart = true;
		}
		else
		{
			if ($numberpage - $rshow > 0)
			{
				$loopstart = $numberpage - $rshow;
				$istart = true;
				$loopend = $numberpage;
			}
			else
			{
				$loopstart = 0;
				$loopend = $numberpage;
			}
		}
	}

	$sPage = '<ul class="'+ $className +'">';
	if ($istart)
		$sPage += '<li><a onclick="javascript:' + $function_name + '(0)" href="javascript:void(0);"><i class="fa fa-fast-backward"></i></a></li>';
	if ($pageindex >= 1)
		$sPage += '<li><a onclick="javascript:' + $function_name + '(' + ($pageindex - 1) + ')" href="javascript:void(0);"><i class="fa fa-step-backward"></i></a></li>';
	for ($i = $loopstart; $i < $loopend; $i++)
	{
		if ($pageindex == $i)
			$sPage += '<li class="' + $classActive + '"><a href="javascript:void(0);">';
		else
			$sPage += '<li><a onclick="javascript:' + $function_name + '(' + $i + ')" href="javascript:void(0);">';
		$sPage += ($i+1) + '</a></li>';
	}
	if ($pageindex <= $numberpage - 2)
		$sPage += '<li><a onclick="javascript:' + $function_name + '(' + ($pageindex + 1) + ')" href="javascript:void(0);" ><i class="fa fa-step-forward"></i></a></li>';
	if ($iend)
		$sPage += '<li><a onclick="javascript:' + $function_name + '(' + ($numberpage - 1) + ')" href="javascript:void(0);" ><i class="fa fa-fast-forward"></i></a></li>';
	$sPage += '</ul>';

	return $sPage;
}

function GetDataInfo(row_count,page_size,page_index){
	if(row_count>0){
		var start_row = page_size*page_index;
		var to_row = (page_size*(page_index+1));
		if(to_row>row_count) to_row=row_count;
		return 'Từ ' + (start_row+1) + ' đến ' + to_row + ' của ' + row_count + ' bản ghi';
	}
	return 'Không có bản ghi nào';
}

function LeftString(s,n){
	if(typeof s ==='string'){
		if(s){
			var length = s.length;
			if(length<=n+3) return s;
			return s.substring(0,n)+'...';
		}
		return s;
	}
	return null;
}

function RightString(s,n){
	if(typeof s ==='string'){
		if(s){
			var length = s.length;
			if(length<=n+3) return s;
			return '...' + s.substring(length-n);
		}
		return s;
	}
	return null;
}

jQuery.fn.ForceNumericOnly = function(){
	return this.each(function(){
		$(this).keydown(function(e){
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
			// home, end, period, and numpad decimal
			return (
			key == 8 ||
			key == 9 ||
			key == 13 ||
			key == 46 ||
			key == 110 ||
			key == 190 ||
			(key >= 35 && key <= 40) ||
			(key >= 48 && key <= 57) ||
			(key >= 96 && key <= 105));
		});
	});
};

jQuery.fn.ForceListNumericOnly = function(){
	return this.each(function(){
		$(this).keydown(function(e){
			var key = e.charCode || e.keyCode || 0;
			// allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
			// home, end, period, comma, and numpad decimal
			return (
			key == 8 ||
			key == 9 ||
			key == 13 ||
			key == 46 ||
			key == 110 ||
			key == 188 ||
			//key == 190 ||
			(key >= 35 && key <= 40) ||
			(key >= 48 && key <= 57) ||
			(key >= 96 && key <= 105));
		});
	});
};

$.fn.scrollBottom = function(scroll){
	if(typeof scroll === 'number'){
		window.scrollTo(0,$(document).height() - $(window).height() - scroll);
		return $(document).height() - $(window).height() - scroll;
	} else {
		return $(document).height() - $(window).height() - $(window).scrollTop();
	}
};

if(!String.prototype.trim) {
	String.prototype.trim = function () {
		return this.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, '');
	};
}

var cloneObject = function(obj){
	if(obj){
		return JSON.parse(JSON.stringify(obj));
	}
	return obj;
};

var getInfoFromList = function(_id, list){
	if(_id && _id != '' && list && list.length>0){
		for(var i=0, info; info = list[i]; i++){
			if(info._id == _id) return info;
		}
	}
	return null;
};

$(document).on("keydown", function (e) {
	if (e.which === 8 && !$(e.target).is("input, textarea")) {
		e.preventDefault();
	}
});

$(document).on('show.bs.modal', '.modal', function (event) {
	var zIndex = 1040 + (10 * $('.modal:visible').length);
	$(this).css('z-index', zIndex);
	setTimeout(function() {
		$('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
	}, 0);
});

$(document).on('hidden.bs.modal', function (event) {
	if ($('.modal:visible').length){
		$('body').addClass('modal-open');
	}
});