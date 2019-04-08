var ddl_parent;
var tb_search;
var bt_search;

var news_data;
var tbody;
var dataTables_info;
var pagination;
var btn_add;

var modal_detail;
var modal_title;
var tb_id;
var tb_name;
var tb_rewrite;
var tb_sort;
var modal_message;
var btn_save;
var modal;
var result_obj;

var ddl_video_type,tb_url,bt_browser,bt_load_info,tb_duration_view,hd_duration,tb_size_view,hd_width,hd_height,tb_format_name,tb_codec_name;

var editor;
var obj_search={};
$(function(){
	$('body').addClass('sidebar-collapse');
	
	//search
	ddl_parent=$('#ddl_parent');
	tb_search = $('#tb_search');
	bt_search = $('#bt_search');
	
	//table
	news_data = $('.news-data');
	tbody = news_data.find('.tbody');
	dataTables_info = news_data.find('.dataTables_info');
	pagination = news_data.find('.dataTables_paginate');
	btn_add = news_data.find('.btn-add');
	
	//modal
	modal_detail = $('#modal_detail');
	modal_title = modal_detail.find('.modal-title');
	modal_message = modal_detail.find('.modal-message');
	btn_save = modal_detail.find('.btn-save');
	
	//edit
	tb_id = $('#tb_id');
	tb_name = $('#tb_name');
	tb_rewrite = $('#tb_rewrite');
	tb_description = $('#tb_description');
	cb_publish_date = $('#cb_publish_date');
	tb_publish_date = $('#tb_publish_date');
	hd_date_from = $('#hd_date_from');
	hd_date_to = $('#hd_date_to');
	tb_tags = $('#tb_tags');
	tb_sort = $('#tb_sort');
	cb_active = $('#cb_active');
	
	ddl_video_type = $('#ddl_video_type');
	tb_url = $('#tb_url');
	bt_browser=$('#bt_browser');
	bt_load_info = $('#bt_load_info');
	tb_duration_view = $('#tb_duration_view');
	hd_duration = $('#hd_duration');
	tb_size_view = $('#tb_size_view');
	hd_width = $('#hd_width');
	hd_height = $('#hd_height');
	tb_format_name = $('#tb_format_name');
	tb_codec_name = $('#tb_codec_name');
	
	//thumb
	img_thumb = $('#img_thumb');
	hd_thumb = $('#hd_thumb');
	hd_no_image = $('#hd_no_image');
	lb_thumb_size = $('#lb_thumb_size');
	bt_change_thumb = $('#bt_change_thumb');
	bt_delete_thumb = $('#bt_delete_thumb');
	
	// var validate = $('#form').validator('validate');
	//modal
	modal = modal_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	modal.on('shown.bs.modal', function () {
		//validate = $('#form').validator('validate');
		//tb_name.focus();
		tb_url.focus();
	});
	modal.on('hide.bs.modal', function () {
		// validate.validator('destroy');
		btn_save.removeAttr('disabled');
		modal_message.hide().text('');
	});
	//end modal
	
	tb_name.keyup(function(){
		var val = $(this).val();
		tb_rewrite.val(write(val));
	});
	tb_name.change(function(){
		var val = $(this).val();
		tb_rewrite.val(write(val));
	});

	btn_add.click(function(){
		if(ddl_parent.find('option').length>0) Fill_Popup(null);
		else{
			bootbox.alert({
				message:'Chưa có thể loại.',
				size: 'small'
			});
		}
	});

	$('#form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message.show('Kiểm tra lại thông tin nhập');
		} else {
			Save_Data();
		}
		return false;
	});
	
	//load data
	tb_search.keyup(function(event){
		var char = event.which || event.keyCode;
		if(char==13){
			bt_search.click();
		}
	});
	ddl_parent.change(function(){
		Parent_Change();
	});
	bt_search.click(function(){
		obj_search.search_key = tb_search.val();
		obj_search.page_index = 0;
		LoadData();
		return false;
	});
	ddl_parent.trigger('change');
	//end load data
	
//	cb_publish_date.click(function(){
//		Cb_Publish_Change();
//	});
	
	$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue'
	});
	//$('#tb_tags').tagsinput('removeAll');
	//$('#tb_tags').tagsinput('add', 'some tag');
	//doc: http://bootstrap-tagsinput.github.io/bootstrap-tagsinput/examples/bootstrap-2.3.2.html
	tb_tags.tagsinput({
		confirmKeys: [13,188],
		trimValue: true,
		allowDuplicates: false,
		tagClass: 'big',
		onTagExists: function(item, $tag) {
			$tag.hide.fadeIn();
		}
	});
	
	tb_publish_date.daterangepicker({
		"autoUpdateInput": false,
		"showDropdowns": true,
		"timePicker": true,
		"timePickerIncrement": 5,
		"timePicker24Hour": true,
		"autoApply": true,
		//"dateLimit": {"days": 30},
		"locale": {
			"format": 'YYYY-MM-DD HH:mm',
			"separator": " -> ",
			"applyLabel": "Ok",
			"cancelLabel": "Hủy",
			"fromLabel": "Từ",
			"toLabel": "Đến",
			"customRangeLabel": "Tùy chọn",
			"daysOfWeek": ["CN","T2","T3","T4","T5","T6","T7"],
			"monthNames": ["Tháng 1","Tháng 2","Tháng 3","Tháng 4","Tháng 5","Tháng 6","Tháng 7","Tháng 8","Tháng 9","Tháng 10","Tháng 11","Tháng 12"],
			"firstDay": 1
		},
		//"startDate": "10/01/2015",
		//"endDate": "10/29/2015",
		"opens": "center",
		"drops": "up"
	}, function(start, end) {
		hd_date_from.val(start.format('YYYY-MM-DD HH:mm'));
		hd_date_to.val(end.format('YYYY-MM-DD HH:mm'));
		tb_publish_date.val(start.format('YYYY-MM-DD HH:mm') + ' -> ' + end.format('YYYY-MM-DD HH:mm'));
	});
	
	//finder
	var finder = new CKFinder();
	finder.defaultLanguage = 'vi';
	finder.language = 'vi';
	finder.removePlugins = 'basket';
	finder.selectActionFunction = function(src){
		SetThumb(src);
	};
	finder.resourceType = 'Video';
	finder.tabIndex = 1;
	finder.startupPath = "Video:/";
	finder.callback = function(api){
		//api.openMsgDialog("Thông báo", "Thumb tin tức nên quy hoạch vào thư mục Images/Thumb_news" );
		api.openFolder('Video', '/');
	};
	
	var finder2 = new CKFinder();
	finder2.defaultLanguage = 'vi';
	finder2.language = 'vi';
	finder2.removePlugins = 'basket';
	finder2.selectActionFunction = function(src){
		tb_url.val(Urllib.decode(src));
	};
	finder2.resourceType = 'Video';
	finder2.tabIndex = 1;
	finder2.startupPath = "Video:/";
	finder2.callback = function(api){
		//api.openMsgDialog("Thông báo", "Thumb tin tức nên quy hoạch vào thư mục Images/Thumb_news" );
		api.openFolder('Video', '/');
	};
	//end finder
	//ck
	CKEDITOR.on('instanceReady', function (ev) {
		ev.editor.commands.save.exec = function(){
			btn_save.click();
			return false;
		}
	});
	editor = CKEDITOR.replace('tb_content');
	//end ck
	bt_change_thumb.click(function(){
		finder.popup('/ckfinder/',600,400);
	});
	bt_delete_thumb.click(function(){
		SetThumb('');
	});
	
	//media
	bt_load_info.click(function(){
		GetVideoInfo();
		return false;
	});
	
	ddl_video_type.change(function(){
		var video_type = ddl_video_type.val();
		if(video_type=='youtube') bt_browser.prop('disabled',true);
		else bt_browser.prop('disabled',false);
	});
	ddl_video_type.trigger('change');
	bt_browser.click(function(){
		finder2.popup('/ckfinder/',600,400);
		return false;
	});
});

//function Cb_Publish_Change(){
//	if(!cb_publish_date.is(':checked')){
//		tb_publish_date.addClass('readonly').attr('disabled','disabled');
//	}
//	else{
//		tb_publish_date.removeClass('readonly').attr('disabled',null);
//	}
//}

function Parent_Change(){
	tb_search.val('');
	obj_search.search_key = '';
	obj_search.page_index = 0;
	obj_search.parent_id = ddl_parent.val();
	obj_search.class_id=ddl_parent.find('option:selected').attr('data-class');
	LoadData();
}

function LoadData(){
	$.ajax({
		url: "/ajax/lesson_cmd.php",
		type:"POST",
		dataType: "json",
		data:{action:'list',class_id:obj_search.class_id,parent_id:obj_search.parent_id,page_index:obj_search.page_index,search_key:obj_search.search_key},
		success: function(data) {
			if (data.error == 0) {
				dataTables_info.text(GetDataInfo(data.row_count,data.page_size,data.page_index));
				pagination.html(GenPageJs(data.row_count,data.page_size,data.page_index,'pagination','active',5,'GoPage'));
				FillData(data.content);
			} else {
				if (data.error == 5) {
					window.location.href='/login.php';
				}
			}
		}
	});
}

function SetThumb(src){
	if(src && src!=''){
		hd_thumb.val(src);
		var img = new Image();
		img.src=src;
		img.onload = function() {
			lb_thumb_size.text('(' + this.width + 'x' + this.height + ')');
		};
		img_thumb.attr('src',src);
	}
	else{
		hd_thumb.val('');
		img_thumb.attr('src',hd_no_image.val());
		lb_thumb_size.text('');
	}
}
function FillData(content){
	var html='';
	result_obj = content;
	if(content){
		$.each(content,function(i,val){
			html+='<tr>';
			html+='<td><img class="thumb" src="'+ (val.thumb && val.thumb!=''?val.thumb:hd_no_image.val()) +'"/></td>';
			html+='<td class="text-center">'+ val._id +'</td>';
			html+='<td>'+ val.name +'</td>';
			//html+='<td>'+ LeftString(val.url,45) +'</td>';
			html+='<td>'+ RightString(val.url,45) +'</td>';
			//html+='<td>'+ (val.is_publish_date?Date2String(val.publish_at) + '<br/>' + Date2String(val.publish_end):'&nbsp;') +'</td>';
			html+='<td class="text-right">'+ val.sort +'</td>';
			html+='<td class="text-center">'+ list_admin[val.create_by] +'</td>';
			html+='<td>'+ Date2String(val.created_at) +'</td>';
			html+='<td class="text-center">'+ (val.active?'Active':'&nbsp;') +'</td>';
			html+='<td class="text-center text-nowrap"><a href="javascript:void(0);" tn-action="edit" onclick="javascript:return ConfirmEdit(' + val._id + ');">Edit</a> | <a href="javascript:void(0);" tn-action="delete" onclick="javascript:return ConfirmDelete(' + val._id + ');">Delete</a></td>';
			html+='</tr>';
		});
	}
	tbody.html(html);
	GoScrollTo('#ddl_parent');
}
function ConfirmDelete(id){
	bootbox.confirm({
		message:'Bạn muốn xóa bản ghi này không?',
		size:'small',
		callback:function(result) {
			if(result){
				$.ajax({
					url: "/ajax/lesson_cmd.php",
					dataType: "json",
					type:"POST",
					data:{action:'delete',id:id, parent_id: ddl_parent.val()},
					// beforeSend: function( xhr ) {
					// 	spinner.show();
					// },
					success:function(data){
						if(data.error==0){
							LoadData();
							ShowMessError('Delete ok!');
						}
						else{
							alert(data.message);
							if(data.error==5){
								window.location.href='/login.php';
							}
						}
					}
				});
				// .always(function() {
				// 	spinner.hide();
				// });
			}
		}
	});
	return false;
}

function ConfirmEdit(id){
	if(result_obj!=null && result_obj.length>0){
		$.each(result_obj,function(i,val){
			if(val._id==id){
				Fill_Popup(val);
				return false;
			}
		});
	}
	return false;
}

function Fill_Popup(obj){
	if(obj){
		modal_title.text('Sửa thông tin bài giảng');
		ddl_video_type.val(obj.video_type);
		tb_url.val(obj.url);
		tb_duration_view.val(obj.duration_view);
		hd_duration.val(obj.duration);
		hd_width.val(obj.width);
		hd_height.val(obj.height);
		tb_size_view.val(obj.width + 'x' + obj.height);
		tb_format_name.val(obj.format_name);
		tb_codec_name.val(obj.codec_name);
		
		SetThumb(obj.thumb);
		tb_id.val(obj._id);
		tb_name.val(obj.name);
		tb_rewrite.val(obj.name_ko_dau);
		tb_description.val(obj.description);
		//cb_publish_date.prop('checked',obj.is_publish_date);
		if(obj.is_publish_date) cb_publish_date.iCheck('check');
		else cb_publish_date.iCheck('uncheck');
		
		if(obj.publish_at!=null && obj.publish_end!=null){
			var sDate_at = Date2String(obj.publish_at);
			var sDate_end = Date2String(obj.publish_end);
			tb_publish_date.data('daterangepicker').setStartDate(sDate_at);
			tb_publish_date.data('daterangepicker').setEndDate(sDate_end);
			tb_publish_date.val(sDate_at + ' -> ' + sDate_end);
			hd_date_from.val(sDate_at);
			hd_date_to.val(sDate_end);
		}
		
		tb_tags.tagsinput('removeAll');
		tb_sort.val(obj.sort);
		//cb_active.prop('checked',obj.active);
		if(obj.active)cb_active.iCheck('check');
		else cb_active.iCheck('uncheck');
		editor.setData(obj.content);
		//set tags
		if(obj.tags && obj.tags.length>0){
			var tags = obj.tags;
			var length = tags.length;
			for(var i=0;i<length;i++){
				tb_tags.tagsinput('add', tags[i]);
			}
		}
	}
	else{
		modal_title.text('Thêm mới thông tin bài giảng');
		//ddl_video_type.val('youtube');
		tb_url.val('');
		tb_duration_view.val('00:00');
		hd_duration.val(0);
		hd_width.val(0);
		hd_height.val(0);
		tb_size_view.val('0x0');
		tb_format_name.val('');
		tb_codec_name.val('');
		
		SetThumb('');
		tb_id.val('0');
		tb_name.val('');
		tb_rewrite.val('');
		tb_description.val('');
		//cb_publish_date.prop('checked',false);
		cb_publish_date.iCheck('uncheck');
		//tb_publish_date.data('daterangepicker').setStartDate(null);
		//tb_publish_date.data('daterangepicker').setEndDate(null);
		tb_publish_date.val('');
		tb_tags.tagsinput('removeAll');
		tb_sort.val('0');
		//cb_active.prop('checked',true);
		cb_active.iCheck('check');
		editor.setData('');
	}
	//Cb_Publish_Change();
	ddl_video_type.trigger('change');
	modal.modal('show');
}

function Save_Data(){
	var thumb = hd_thumb.val();
	var id = parseInt(tb_id.val());
	
	var video_type = ddl_video_type.val();
	var url = tb_url.val();
	var duration_view = tb_duration_view.val();
	var duration = hd_duration.val();
	var width = hd_width.val();
	var height = hd_height.val();
	//tb_size_view
	var format_name = tb_format_name.val();
	var codec_name = tb_codec_name.val();
	var class_id=ddl_parent.find('option:selected').attr('data-class');
	
	var name = tb_name.val();
	//var name_ko_dau = tb_rewrite.val();
	var name_ko_dau = write(name);
	var parent_id = ddl_parent.val();
	var parent_name = ddl_parent.find('option:selected').text();
	var description = tb_description.val();
	var is_publish_date = cb_publish_date.prop('checked');
	var publish_at = hd_date_from.val();
	var publish_end = hd_date_to.val();
	var tags = tb_tags.val();
	var active = cb_active.prop('checked');
	var sort = tb_sort.val();
	var content = editor.getData();//e
	//console.log('tags',tags);
	$.ajax({
		url: "/ajax/lesson_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'save',video_type:video_type,url:url,duration_view:duration_view,duration:duration,width:width,height:height,format_name:format_name,codec_name:codec_name,class_id:class_id,thumb:thumb,id:id,name:name,name_ko_dau:name_ko_dau,parent_id:parent_id,parent_name:parent_name,sort:sort,description:description,is_publish_date:is_publish_date,publish_at:publish_at,publish_end:publish_end,active:active,tags:tags,content:content},
		beforeSend: function( xhr ) {
			spinner.show();
			modal_message.show().text('Đang thao tác...');
		},
		success:function(data){
			if(data.error==0){
				LoadData();
				if(id>0){
					modal_message.show().text('Update thành công');
				}
				else{
					modal_message.show().text('Insert thành công');
				}
				setTimeout(function(){
					modal.modal('hide');
				}, 1000);
			}
			else{
				modal_message.show().text(data.message);
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
	})
	.always(function() {
		spinner.hide();
		btn_save.removeAttr('disabled');
	});
}

function GetVideoInfo(){
	var url = tb_url.val().trim();
	if(url!=''){
		var video_type = ddl_video_type.val();
		if(video_type=='youtube'){
			Youtube.GetInfo(url,function(data){
				if(!data.error){
					tb_duration_view.val(data.duration_view);
					hd_duration.val(data.duration_second);
					tb_name.val(data.title);
					SetThumb(data.thumb);
					tb_tags.tagsinput('removeAll');
					if(data.tags && data.tags.length>0){
						var tags = data.tags;
						var length = tags.length;
						for(var i=0;i<length;i++){
							tb_tags.tagsinput('add', tags[i]);
						}
					}
					tb_description.val(data.description.replace(/\n/gi,'<br/>'));
				}
				tb_size_view.val('0x0');
				hd_width.val(0);
				hd_height.val(0);
				tb_format_name.val('');
				tb_codec_name.val('');
			});
		}
		else if(video_type=='mp4' || video_type=='m3u8'){
			MEDIA.GetInfo(url,function(data){
				if(data.error){
					bootbox.alert({
						message:data.message,
						size: 'small'
					});
					return;
				}
				tb_duration_view.val(data.duration_view);
				hd_duration.val(data.duration_second);
				if(data.width && data.height){
					tb_size_view.val(data.width +'x'+ data.height);
					hd_width.val(data.width);
					hd_height.val(data.height);
				}
				else{
					tb_size_view.val('0x0');
					hd_width.val(0);
					hd_height.val(0);
				}
				if(data.codec_name) tb_codec_name.val(data.codec_name);
				else tb_codec_name.val('');
				if(data.format_name) tb_format_name.val(data.format_name);
				else tb_format_name.val('');
			});
		}
	}
	else{
		bootbox.alert({
			message:'Hãy nhập url của video',
			size: 'small',
			callback: function() {
				tb_url.focus();
			}
		});
	}
}

function GoPage(page_index){
	obj_search.page_index = page_index;
	LoadData();
}