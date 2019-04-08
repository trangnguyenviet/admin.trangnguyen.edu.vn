var tb_id,tb_username,tb_fullname,tb_email,tb_mobile,ddl_province,ddl_district,ddl_school,ddl_class,bt_search,rb_id,rb_username,rb_fullname,rb_email,rb_mobile,rb_address,
	hd_no_image,
	dataTables_info,tbody,dataTables_paginate,content_info,lb_info_search,
	modal,modal_detail,modal_title,tb_id_edit,tb_username_edit,tb_name_edit,tb_email_edit,cb_active_edit,tb_mobile_edit,tb_mobile_edit_show,tb_birthday_edit,ddl_province_edit,ddl_district_edit,ddl_school_edit,tb_class_id_edit,tb_class_name_edit,tb_create_at_edit,tb_update_at_edit,modal_message,btn_save,
	result_obj,obj_search={};
var current_user_id;
var modal_password,modal_change_password,tb_id_pass_edit,tb_username_pass_edit,tb_password_edit,modal_message_password;
var modal_score_detail,tb_id_score_edit,tb_username_score_edit,ddl_exam_type,tbody_score,modal_message_score,btn_update_score,rank_score_national,rank_score_province,rank_score_district,rank_score_school;
var modal_payment_detail,tb_id_payment_edit,tb_username_payment_edit,ddl_payment_done,tbody_payment,payment_dataTables_info,payment_dataTables_paginate;
var modal_add_vip,tb_id_vip_edit,tb_username_vip_edit,tb_vip_day_edit,tb_add_day_edit,modal_message_vip,tb_add_day_note;
var modal_score_cache,tb_id_score_cache,tb_username_score_cache,tb_connect_cache,ddl_exam_type_cache,tb_round_cache,modal_message_cache,btn_delete_score;
var rb_exam_district,ddl_exam_district_level_province,ddl_exam_district_level_district,ddl_exam_district_level_class;
var tb_list_id;
var modal_email, ddl_email_from, tb_email_to, tb_email_subject, tb_email_body, lb_message_email, email_editor;
var modal_exam_detail;
var modal_payment_hand_detail, tb_id_payment_hand_edit, tb_username_payment_hand_edit, tb_payment_hand_expire;
$(function(){
	$('body').addClass('sidebar-collapse');
	$('.col-ext').addClass('hide');

	tb_id = $('#tb_id');
	tb_list_id = $('#tb_list_id');
	tb_username = $('#tb_username');
	tb_fullname = $('#tb_fullname');
	tb_email = $('#tb_email');
	tb_mobile = $('#tb_mobile');
	ddl_province = $('#ddl_province');
	ddl_district = $('#ddl_district');
	ddl_school = $('#ddl_school');
	ddl_class = $('#ddl_class');
	bt_search = $('#bt_search');

	rb_id = $('#rb_id');
	rb_username = $('#rb_username');
	rb_fullname = $('#rb_fullname');
	rb_email = $('#rb_email');
	rb_mobile = $('#rb_mobile');
	rb_address = $('#rb_address');

	rb_exam_district = $('#rb_exam_district');
	ddl_exam_district_level_province = $('#ddl_exam_district_level_province');
	ddl_exam_district_level_district = $('#ddl_exam_district_level_district');
	ddl_exam_district_level_class = $('#ddl_exam_district_level_class');

	rb_exam_province = $('#rb_exam_province');
	ddl_exam_province = $('#ddl_exam_province');

	hd_no_image = $('#hd_no_image');
	content_info = $('#content_info');
	lb_info_search = content_info.find('.lb-info-search');
	tbody = content_info.find('.tbody');
	dataTables_info = content_info.find('.dataTables_info');
	dataTables_paginate = content_info.find('.dataTables_paginate');
	bt_export = $('.btn-export');

	//modal detail
	modal_detail = $('#modal_detail');
	modal_title = modal_detail.find('.modal-title');
	tb_id_edit = $('#tb_id_edit');
	tb_username_edit = $('#tb_username_edit');
	tb_password_edit = $('#tb_password_edit');
	tb_name_edit = $('#tb_name_edit');
	tb_email_edit = $('#tb_email_edit');
	cb_active_edit = $('#cb_active_edit');
	tb_mobile_edit = $('#tb_mobile_edit');
	tb_mobile_edit_show = $('#tb_mobile_edit_show');
	tb_birthday_edit = $('#tb_birthday_edit');
	ddl_province_edit = $('#ddl_province_edit');
	ddl_district_edit = $('#ddl_district_edit');
	ddl_school_edit = $('#ddl_school_edit');
	tb_class_id_edit = $('#tb_class_id_edit');
	tb_class_name_edit = $('#tb_class_name_edit');
	tb_create_at_edit = $('#tb_create_at_edit');
	tb_update_at_edit = $('#tb_update_at_edit');
	modal_message = modal_detail.find('.modal-message');
	btn_save = modal_detail.find('.btn-save');
	modal = modal_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	//end modal detail

	//modal password
	modal_change_password = $('#modal_change_password');
	tb_id_pass_edit = $('#tb_id_pass_edit');
	tb_username_pass_edit = $('#tb_username_pass_edit');
	tb_password_edit = $('#tb_password_edit');
	modal_message_password = modal_change_password.find('.modal-message');
	modal_password = modal_change_password.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	$('#modal_change_password form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message_password.show('Kiểm tra lại thông tin nhập');
		} else {
			SaveChangePassword();
		}
		return false;
	});
	//end modal password

	// modal modal_exam_detail
	modal_exam_detail = $('#modal_exam_detail').modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	// end modal_exam_detail

	//modal score
	modal_score_detail = $('#modal_score_detail');
	tb_id_score_edit = $('#tb_id_score_edit');
	tb_username_score_edit = $('#tb_username_score_edit');
	ddl_exam_type = $('#ddl_exam_type');
	tbody_score = modal_score_detail.find('.tbody');
	modal_message_score = modal_score_detail.find('.modal-message');
	btn_update_score = modal_score_detail.find('.btn-update');
	rank_score_national = $('#rank_score_national');
	rank_score_province = $('#rank_score_province');
	rank_score_district = $('#rank_score_district');
	rank_score_school = $('#rank_score_school');
	modal_score = modal_score_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	ddl_exam_type.change(function(){
		LoadDataScore(current_user_id);
	});
	btn_update_score.click(function(){
		UpdateRankScore(current_user_id);
		return false;
	});

	//--modal add score
	var ddl_exam_type_add = $('#ddl_exam_type_add'),
		tb_round_add = $('#tb_round_add'),
		tb_round_score = $('#tb_round_score'),
		tb_round_time = $('#tb_round_time'),
		tb_round_luot = $('#tb_round_luot'),
		tb_round_code = $('#tb_round_code'),
		tb_round_date = $('#tb_round_date');
	modal_message_score_add = $('#modal_score_add .modal-message');
	var modal_score_add = $('#modal_score_add').modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});

	tb_round_date.daterangepicker({
		"singleDatePicker": true,
		"showDropdowns": true,
		"timePicker": true,
		"timePicker24Hour": true,
		"timePickerSeconds": true,
		"alwaysShowCalendars": true,
		"locale": {
			"format": 'YYYY-MM-DD HH:mm:ss',
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
		//"startDate": "11/10/2015",
		//"endDate": "11/16/2015",
		"opens": "center"
	}, function(start, end, label) {
		//console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
	});

	modal_score_detail.find('.btn-add').click(function(){
		ddl_exam_type_add.val(ddl_exam_type.val());
		tb_round_code.val('');
		tb_round_date.val('');
		tb_round_score.val('');
		tb_round_time.val('');
		tb_round_luot.val(1);
		tb_round_add.val('');
		modal_message_score_add.text('');
		modal_score_add.modal('show');
		setTimeout(function(){
			ddl_exam_type_add.focus();
		},200);
	});

	if(!window.admin_info || window.admin_info.id !== 1) {
		modal_score_detail.find('.btn-add').hide();
	}

	$('#modal_score_add form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message_password.show('Kiểm tra lại thông tin nhập');
		} else {
			var data = {
				user_id: current_user_id,
				type_id: ddl_exam_type_add.val(),
				date_create: tb_round_date.val(),
				round_id: tb_round_add.val(),
				score: tb_round_score.val(),
				time: tb_round_time.val(),
				luot: tb_round_luot.val(),
				code: tb_round_code.val()
			};
			if(data.date_create==''){
				modal_message_score_add.text('Hãy nhập ngày thi');
				return false;
			}
			if(data.round_id==''){
				modal_message_score_add.text('Hãy nhập vòng thi');
				return false;
			}
			if(data.score==''){
				modal_message_score_add.text('Hãy nhập điểm thi');
				return false;
			}
			if(data.time==''){
				modal_message_score_add.text('Hãy nhập thời gian thi');
				return false;
			}
			if(data.luot==''){
				modal_message_score_add.text('Hãy nhập lượt thi');
				return false;
			}
			data.action='save';
			$.ajax({
				url: "/ajax/score_cmd.php",
				type:'POST',
				dataType: "json",
				data:data,
				beforeSend: function( xhr ) {
					spinner.show();
					modal_message_score_add.show().text('Đang thao tác...');
				},
				success:function(data){
					if(data.error==0){
						modal_message_score_add.show().text('Thêm thành công');
						LoadDataScore(current_user_id);
						setTimeout(function(){
							modal_score_add.modal('hide');
						}, 1000);
					}
					else{
						modal_message_score_add.show().text(data.message);
						if(data.error==5){
							Alear(data.message, function() {
								window.location.href='/login.php';
							});
						}
					}
				}
			})
				.always(function() {
					spinner.hide();
				});
		}
		return false;
	});
	//--end modal add score
	//end modal score

	//modal payment
	modal_payment_detail = $('#modal_payment_detail');
	tb_id_payment_edit = $('#tb_id_payment_edit');
	tb_username_payment_edit = $('#tb_username_payment_edit');
	ddl_payment_done = $('#ddl_payment_done');
	tbody_payment = modal_payment_detail.find('.tbody');
	payment_dataTables_info = modal_payment_detail.find('.dataTables_info');
	payment_dataTables_paginate = modal_payment_detail.find('.dataTables_paginate');

	modal_payment = modal_payment_detail.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	ddl_payment_done.change(function(){
		LoadDataPayment(current_user_id,0);
	});
	//end modal payment

	// modal payment hand
	modal_payment_hand_detail = $('#modal_payment_hand_detail').modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});

	tb_id_payment_hand_edit = $('#tb_id_payment_hand_edit');
	tb_username_payment_hand_edit = $('#tb_username_payment_hand_edit');
	tb_payment_hand_expire = $('#tb_payment_hand_expire');
	// end modal payment hand

	//modal vip
	modal_add_vip = $('#modal_add_vip');
	tb_id_vip_edit = $('#tb_id_vip_edit');
	tb_username_vip_edit = $('#tb_username_vip_edit');
	tb_vip_day_edit = $('#tb_vip_day_edit');
	tb_add_day_edit = $('#tb_add_day_edit');
	tb_add_day_note = $('#tb_add_day_note');
	modal_message_vip = $('#modal_add_vip .modal-message');
	modal_vip = modal_add_vip.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
	$('#modal_add_vip form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message_vip.show('Kiểm tra lại thông tin nhập');
		} else {
			SaveVip();
		}
		return false;
	});
	//end modal vip

	//modal cache
	modal_score_cache = $('#modal_score_cache');
	tb_id_score_cache = $('#tb_id_score_cache');
	tb_username_score_cache = $('#tb_username_score_cache');
	tb_connect_cache = $('#tb_connect_cache');
	ddl_exam_type_cache = $('#ddl_exam_type_cache');
	tb_round_cache = $('#tb_round_cache');
	modal_message_cache = $('#modal_score_cache .modal-message');
	btn_delete_score = $('#modal_score_cache .btn-delete');

	modal_cache = modal_score_cache.modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});

	$('#modal_score_cache form').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message_cache.show('Kiểm tra lại thông tin nhập');
		} else {
			var user_id = parseInt(tb_id_score_cache.val());
			var type_id = ddl_exam_type_cache.val();
			var round_id = tb_round_cache.val();
			$.ajax({
				url: "/ajax/score_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action:'delete_score_cache',user_id:user_id,type_id:type_id,round_id:round_id},
				beforeSend: function( xhr ) {
					spinner.show();
					modal_message_cache.show().text('Đang thao tác...');
				},
				success:function(data){
					if(data.error==0){
						modal_message_cache.show().text('Xóa thành công');
						setTimeout(function(){
							modal_cache.modal('hide');
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
		return false;
	});
	//end modal cache

	// modal email
	ddl_email_from = $('#ddl_email_from');
	tb_email_to= $('#tb_email_to');
	tb_email_subject = $('#tb_email_subject');
	tb_email_body = $('#tb_email_body');
    lb_message_email = $('#modal_email').find('.modal-message');
    modal_email = $('#modal_email').modal({
        show: false,
        keyboard: false,
        backdrop: 'static'
    });

    //ck
    CKEDITOR.on('instanceReady', function (ev) {
        ev.editor.commands.save.exec = function(){
            btn_save.click();
            return false;
        };
    });
    email_editor = CKEDITOR.replace('tb_email_body');
    //end ck

    $('#modal_email form').on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            lb_message_email.show('Kiểm tra lại thông tin nhập');
        } else {
            var from = ddl_email_from.val();
            var fromName = ddl_email_from.find('option:selected').text();
            var to = tb_email_to.val();
            var subject = tb_email_subject.val();
            var body = email_editor.getData();

            if(to === '') {
                return lb_message_email.text('Hãy nhập người nhận');
			}

			var arr_email = to.split(/,/g);
			for(var i = 0; i < arr_email.length; i++) {
				var email  = arr_email[i].trim();
                if(!util.isEmail(email)) {
                    return lb_message_email.text('Hãy nhập người nhận');
                }
			}

            $.ajax({
                url: "/ajax/user_cmd.php",
                type:'POST',
                dataType: "json",
                data:{action: 'send_email', from, fromName, to, subject, body},
                beforeSend: function( xhr ) {
                    spinner.show();
                    lb_message_email.show().text('Đang thao tác...');
                },
                success:function(data){
                    if(data.error==0){
                        lb_message_email.show().text('Gửi thành công');
                        setTimeout(function(){
                            modal_email.modal('hide');
                        }, 1000);
                    }
                    else{
                        lb_message_email.show().text(data.message);
                        if(data.error == 5) {
                            bootbox.alert({
                                message: data.message,
                                size: 'small',
                                callback: function() {
                                    window.location.href = '/login.php';
                                }
                            });
                        }
                    }
                }
            })
			.always(function() {
				spinner.hide();
			});
        }
        return false;
    });
	// end modal email

	//$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
	$('input[type="checkbox"], input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_minimal-blue',
		radioClass: 'iradio_minimal-blue'
	});

	$('input').on('ifChecked', function(event){
		var control_id = $(this).attr('control-focus');
		if(control_id && control_id!='') $('#' + control_id).focus();
	});

	//event
	tb_list_id.ForceListNumericOnly();
	tb_list_id.blur(function(){
		var tb = $(this);
		tb.val(tb.val().replace(/[^0-9,]/g, ',').replace(/\,{2,}/g,',').replace(/^,(.+)$/, '$1').replace(/(.+),$/, '$1'));
	});

	ddl_province.change(function(){
		var province_id = ddl_province.val();
		if(province_id>0) {
			LoadDataDistrict(province_id);
		}
		else ddl_district.val(0).prop('disabled',true).html('<option value="0">Tất cả huyện</option>');
		ddl_school.val(0).prop('disabled',true);
		ddl_class.val(0).prop('disabled',true);
	});
	ddl_district.change(function(){
		var district_id = Number(ddl_district.val());
		if(district_id>0) {
			LoadDataSchool(district_id);
		}
		else ddl_school.val(0).prop('disabled',true).html('<option value="0">Tất cả trường</option>');
		ddl_class.val(0).prop('disabled',true);
	});
	ddl_school.change(function(){
		var school_id = Number(ddl_school.val());
		if(school_id>0) ddl_class.prop('disabled',false);
		else ddl_class.val(0).prop('disabled',true);
	});

	ddl_province_edit.change(function(){
		var province_id = ddl_province_edit.val();
		if(province_id>0) {
			LoadDataDistrictEdit(province_id,0);
		}
		else ddl_district_edit.val(0).prop('disabled',true).html('<option value="0">Chọn huyện</option>');
		ddl_school_edit.val(0).prop('disabled',true);
	});
	ddl_district_edit.change(function(){
		var district_id = Number(ddl_district_edit.val());
		if(district_id>0) {
			LoadDataSchoolEdit(district_id,0);
		}
		else ddl_school_edit.val(0).prop('disabled',true).html('<option value="0">Chọn trường</option>');
	});

	ddl_exam_district_level_province.change(function(){
		var province_id = ddl_exam_district_level_province.val();
		if(province_id>0) {
			LoadDataDistrictEdit(province_id,0,ddl_exam_district_level_district);
		}
		else ddl_exam_district_level_district.val(0).prop('disabled',true).html('<option value="0">Chọn huyện</option>');
	});

	bt_export.click(function(){
		obj_search.action = 'export';
		// window.open('/ajax/user_cmd.php?' + $.param(obj_search));
		util.post('/ajax/user_cmd.php',obj_search);
	});
	//end event
	tb_id.ForceNumericOnly();
	tb_mobile.ForceNumericOnly();
	tb_mobile_edit.ForceNumericOnly();

	//
	tb_birthday_edit.daterangepicker({
		"singleDatePicker": true,
		"showDropdowns": true,
		"locale": {
			"format": 'YYYY-MM-DD',
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
		//"startDate": "11/10/2015",
		//"endDate": "11/16/2015",
		"opens": "center"
	}, function(start, end, label) {
		//console.log("New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')");
	});
	//

	bt_search.click(function(){
		if(rb_id.prop('checked')){
			var id = tb_id.val();
			id = Number(id);
			if(id>0){
				obj_search.search_type='id';
				obj_search.id=id;
				obj_search.page_index = 0;
				LoadData(obj_search);
			}
			else Alert('Hãy nhập user id',function(){
				tb_id.focus();
			});
		}
		else if($('#rb_list_id').prop('checked')){
			obj_search.search_type='list_id';
			obj_search.list_id=tb_list_id.val();
			obj_search.page_index = 0;
			LoadData(obj_search);
		}
		else if(rb_username.prop('checked')){
			var username = tb_username.val().trim();
			if(username!=''){
				obj_search.search_type='username';
				obj_search.username=username;
				obj_search.page_index = 0;
				LoadData(obj_search);
			}
			else Alert('Hãy nhập username',function(){
				tb_username.focus();
			});
		}
		else if(rb_fullname.prop('checked')){
			var fullname = fullname = tb_fullname.val().trim();
			if(fullname!=''){
				obj_search.search_type='fullname';
				obj_search.fullname=fullname;
				obj_search.page_index = 0;
				LoadData(obj_search);
			}
			else Alert('Hãy nhập fullname',function(){
				tb_fullname.focus();
			});
		}
		// else if(rb_email.prop('checked')){
		// 	var email = tb_email.val().trim();
		// 	if(email!=''){
		// 		obj_search.search_type='email';
		// 		obj_search.email=email;
		// 		obj_search.page_index = 0;
		// 		LoadData(obj_search);
		// 	}
		// 	else Alert('Hãy nhập email',function(){
		// 		tb_email.focus();
		// 	});
		// }
		else if(rb_mobile.prop('checked')){
			var mobile = mobile = tb_mobile.val().trim();
			if(mobile!=''){
				obj_search.search_type='mobile';
				obj_search.mobile=mobile;
				obj_search.page_index = 0;
				LoadData(obj_search);
			}
			else Alert('Hãy nhập mobile',function(){
				tb_mobile.focus();
			});
		}
		else if(rb_address.prop('checked')){
			obj_search.search_type='address';
			obj_search.province_id=ddl_province.val();
			obj_search.district_id=ddl_district.val();
			obj_search.school_id=ddl_school.val();
			obj_search.class_id=ddl_class.val();
			obj_search.page_index = 0;
			LoadData(obj_search);
		}
		else if(rb_exam_district.prop('checked')){
			obj_search.search_type='exam_district';
			obj_search.province_id=ddl_exam_district_level_province.val();
			obj_search.district_id=ddl_exam_district_level_district.val();
			obj_search.class_id=ddl_exam_district_level_class.val();
			obj_search.page_index = 0;
			LoadData(obj_search);
		}
		else if(rb_exam_province.prop('checked')){
			obj_search.search_type='exam_province';
			obj_search.province_id=ddl_exam_province.val();
			obj_search.class_id=$('#ddl_exam_class').val();
			obj_search.page_index = 0;
			LoadData(obj_search);
		}
		else if($('#rb_exam_national').prop('checked')){
			obj_search.search_type='exam_national';
			obj_search.province_id=$('#ddl_exam_national').val();
			obj_search.class_id=$('#ddl_exam_class').val();
			obj_search.page_index = 0;
			LoadData(obj_search);
		}
		else if($('#rb_award').prop('checked')){
			var award = $('#tb_award').val();
			if(award!=''){
				obj_search.search_type='award';
				obj_search.award=award;
				obj_search.province_id=$('#ddl_award_province').val();
				obj_search.class_id=$('#ddl_award_class').val();
				obj_search.page_index = 0;
				LoadData(obj_search);
			}
			else Alert('Hãy nhập giải thưởng',function(){
				$('#tb_award').focus();
			});
		}
		else if($('#rb_score').prop('checked')){
			var ddl_exam_class = $('#ddl_exam_class');
			var tb_score_code = $('#tb_score_code');
			var code = tb_score_code.val();
			var class_id = $('#ddl_code_class').val();
			if(code!=''){
				obj_search.search_type='score';
				obj_search.code=code;
				obj_search.class_id=class_id;
				obj_search.page_index = 0;
				LoadData(obj_search);
			}
			else Alert('Hãy nhập mã thi',function(){
				tb_score_code.focus();
			});
		}
		else if($('#rb_payment').prop('checked')){
			obj_search.search_type='payment';
			obj_search.province_id=$('#ddl_payment_province').val();
			obj_search.page_index = 0;
			LoadData(obj_search);
		}
		else Alert('Hãy chọn một kiểu để tìm kiếm');
		return false;
	});

	$('#form_detail').on('submit', function (e) {
		if (e.isDefaultPrevented()) {
			modal_message.show('Kiểm tra lại thông tin nhập');
		} else {
			Save_Data();
		}
		return false;
	});
});

function LoadDataDistrict(id){
	$.ajax({
		url: "/ajax/district_cmd.php",
		type:"POST",
		dataType: "json",
		data:{action:'list',province_id:id},
		success: function(data) {
			//console.log(data);
			if (data.error == 0) {
				var html='<option value="0">Tất cả huyện</option>';
				if(data.content && data.content.length>0)
					$.each(data.content,function(index,val){
						html+='<option value="' + val._id + '">' + val.name + '</option>';
					});
				ddl_district.html(html).prop('disabled',false);
			} else {
				Alert(data.message, function(){
					if (data.error == 5) window.location.href='/login.php';
				});
			}
		}
	});
}

function LoadDataSchool(id){
	$.ajax({
		url: "/ajax/school_cmd.php",
		type:"POST",
		dataType: "json",
		data:{action:'list',district_id:id},
		success: function(data) {
			//console.log(data);
			if (data.error === 0) {
				var html='<option value="0">Tất cả trường</option>';
				if(data.content && data.content.length>0)
					$.each(data.content,function(index,val){
						html+='<option value="' + val._id + '">' + val.name + '</option>';
					});
				ddl_school.html(html).prop('disabled',false);
			} else {
				Alert(data.message, function(){
					if (data.error == 5) window.location.href='/login.php';
				});
			}
		}
	});
}

function LoadData(obj_search){
	obj_search.action='list';
	$.ajax({
		url: "/ajax/user_cmd.php",
		type:"POST",
		dataType: "json",
		data:obj_search,
		success: function(data) {
			if (data.error == 0) {
				lb_info_search.text(data.row_count + ' người dùng phù hợp');
				dataTables_info.text(GetDataInfo(data.row_count, data.page_size, data.page_index));
				dataTables_paginate.html(GenPageJs(data.row_count, data.page_size, data.page_index, 'pagination', 'active', 5, 'GoPage'));
				FillData(data.content, data.page_size, data.page_index);
			} else {
				Alert(data.message, function(){
					if (data.error === 5) window.location.href='/login.php';
				});
			}
		}
	});
}

function FillData(content, page_size, page_index){
	var html='';
	result_obj = content;
	if(content){
		$.each(content,function(i, val){
			html+='<tr>';
			//html+='<td><img class="thumb" src="http://trangnguyen.edu.vn/avatar/'+ val._id +'"/></td>';
			html+='<td class="text-center">'+ (page_size * page_index + i + 1) +'</td>';
			html+='<td>ID: <strong class="data-key">' + val._id + '</strong><br/><a href="javascript:void(0)" onclick="ConfirmEdit(' + val._id + ')">' + val.username +'</a href="javascript:void(0)" onclick="ConfirmEdit(' + val._id + ')"></td>';
			html+='<td>'+ val.name +'</td>';
			html+='<td><strong>' + val.class_id + '</strong> (' + val.class_name + ')<br/>'+ (val.birthday?Date2String2(val.birthday):'&nbsp;') +'</td>';
			html+='<td>'+ (val.email? '<a href="javascript:void(0);" onclick="FillModalEmail(' + val._id + ')" >' + val.email + '</a><br>': '') + (val.mobile? util.hidenMobi(val.mobile) + '<br/>': '') + Date2String(val.created_at) +'</td>';
			html+='<td><strong>' + val.province_name + '</strong> (' + val.province_id + ')<br/><strong>' + val.district_name + '</strong> (' + val.district_id + ') <br/><strong>' + val.school_name + '</strong> (' + val.school_id + ')</td>';
			//html+='<td>'+ val.money +'</td>';
			//html+='<td class="text-center">'+ (val.banned?'x':'&nbsp;') +'</td>';
			html+='<td class="col-ext" style="color:#e00">'+ (val.score?val.score:'&nbsp;') +'</td>';
			html+='<td class="col-ext" style="color:#e00">'+ (val.time?val.time:'&nbsp;') +'</td>';
			html+='<td class="col-ext" style="color:#e00">'+ (val.round_id?val.round_id:'&nbsp;') +'</td>';
			html+='<td><a href="javascript:void(0)" onclick="FillModalScore(' + val._id + ')"> Vòng: <strong>'+ val.current_round_4 + '</strong><br/> Điểm: <strong>' + val.total_score_4 + '</strong><br/>Tgian: <strong>' + val.total_time_4 +'</strong></a></td>';
			html+='<td>'+ (val.vip_expire? '<a href="javascript:void(0)" onclick="return showHistoryHandPayment(' + val._id + ')">' + Date2String(val.vip_expire) + '</a>': '') +'</td>';
			html+='<td class="text-center">' +
				(val.active?'<i class="fa fa-check-square-o text-green"></i>':'<i class="fa fa-square-o text-red"></i>') + '&nbsp;' +
				'<a href="javascript:void(0);" title="Khóa/mở account" onclick="return Ban(' + val._id + ', ' + !val.banned + ')">'+ (val.banned?'<i class="fa fa-check-square-o text-red"></i>':'<i class="fa fa-square-o text-green"></i>') +'</a> ' +
				//(val.banned?'<i class="fa fa-check-square-o text-red"></i>':'<i class="fa fa-square-o text-green"></i>') + '&nbsp;' +
				'<a href="javascript:void(0);" title="Thi cấp trường" onclick="return Set_Exam_School('+val._id+','+val.exam_school+')">'+ (val.exam_school?'<i class="fa fa-check-square-o"></i>':'<i class="fa fa-square-o"></i>') +'</a> ' +
				'<a href="javascript:void(0);" title="Thi cấp huyện" onclick="return Set_Exam_District('+val._id+','+val.exam_district+')">'+ (val.exam_district?'<i class="fa fa-check-square-o"></i>':'<i class="fa fa-square-o"></i>') +'</a> ' +
				'<a href="javascript:void(0);" title="Thi cấp tỉnh" onclick="return Set_Exam_Province('+val._id+','+val.exam_province+')">'+ (val.exam_province?'<i class="fa fa-check-square-o"></i>':'<i class="fa fa-square-o"></i>') +'</a> ' +
				'<a href="javascript:void(0);" title="Thi quốc gia" onclick="return Set_Exam_National('+val._id+','+val.exam_national+')">'+ (val.exam_national?'<i class="fa fa-check-square-o"></i>':'<i class="fa fa-square-o"></i>') +'</a> ' +
				'</td>';
			//html+='<td class="text-center"><a href="javascript:void(0);" onclick="return Set_Exam_National('+val._id+','+val.is_exam_national+')">'+ (val.is_exam_national?'<i class="fa fa-check-square-o"></i>':'<i class="fa fa-square-o"></i>') +'</a></td>';
			html+='</tr>';
		});
	}
	tbody.html(html);
	content_info.show();
	init_contextMenu();
	GoScrollTo('#content_info');

	if(obj_search.search_type=='score'){
		$('.col-ext').removeClass('hide');
	}
	else{
		$('.col-ext').addClass('hide');
	}
}

function ConfirmDelete(id){
	// bootbox.confirm({
	// 	message:'Bạn muốn xóa người dùng này không?',
	// 	size:'small',
	bootbox.prompt({
		title:'Bạn muốn xóa người dùng này không? <br>Lý do?',
		callback:function(result) {
			if(result){
				$.ajax({
					url: "/ajax/user_cmd.php",
					dataType: "json",
					type: "POST",
					data:{action: 'delete', id: id, reason: result},
					success:function(data){
						if(data.error==0){
							LoadData(obj_search);
							ShowMessError('Delete ok!');
						}
						else{
							bootbox.alert({
								message:data.message,
								size: 'small',
								callback: function() {
									if(data.error==5){
										window.location.href='/login.php';
									}
								}
							});
						}
					}
				});
			} else if(result === '') {
				Alert('Không có lý do, account chưa bị xóa!');
			}
		}
	});
	return false;
}

function Ban(id, status){
	var msg = status? 'Bạn muốn khóa người dùng này không?': 'Bạn muốn kích hoạt người dùng này không?';
	// Confirm(msg, function(result){
	Prompt(msg + '<br> Lý do?', '', function(result){
		if(result){
			$.ajax({
				url: "/ajax/user_cmd.php",
				dataType: "json",
				type: "POST",
				data: { action: 'ban', id: id, status: status, reason: result },
				success: function(data) {
					if(data.error === 0) {
						LoadData(obj_search);
						ShowMessError('Update ok!');
					} else{
						Alert(data.message, function() {
							if(data.error === 5) {
								window.location.href='/login.php';
							}
						});
					}
				}
			});
		} else if(result === '') {
			Alert('Không có lý do, thao tác chưa được thực hiện');
		}
	});
	return false;
}


function init_contextMenu(){
	$("#content_info td").contextMenu({
		menuSelector: "#contextMenu",
		menuSelected: function (invokedOn, selectedMenu) {
			// console.log(selectedMenu, invokedOn[0].localName);
			var id = invokedOn[0].localName === 'td'? invokedOn.parent().find('.data-key').text(): invokedOn.parent().parent().find('.data-key').text();
			var action =  selectedMenu.attr('tn-ct-action');

			switch (action) {
				case 'info':
					ConfirmEdit(id);
					break;
				case 'score':
					FillModalScore(id);
					break;
				case 'change_password':
					FillModalPassword(id);
					break;
				case 'delete-score-cache':
					ShowModalCache(id);
					break;
				case 'delete_avatar':
					ConfirmDeleteAvatar(id);
					break;
				case 'delete':
					ConfirmDelete(id);
					break;
				case 'ban':
					Ban(id,true);
					break;
				case 'unban':
					Ban(id,false);
					break;

				case 'send_email':
					FillModalEmail(id);
					break;
				case 'send_sms':
					FillModalSms(id);
					break;

				case 'log_login':

					break;
				case 'log_payment':
					FillModalPayment(id);
					break;
				case 'log_payment_hand':
					showHistoryHandPayment(id);
					break;
				case 'add-expire-day':
					FillModalVip(id);
					break;
				default:
					break;
			}
		}
	});
}

function GetUserInfo(id){
	if(result_obj && result_obj.length > 0) {
		for(var i = 0; i < result_obj.length; i++) {
			var val = result_obj[i];
			if(val._id == id) {
				return val;
				break;
			}
		}
	}
	return null;
}

function ConfirmEdit(id){
	var val = GetUserInfo(id);
	if(val) Fill_Popup(val);
	return false;
}
//
//function ConfirmDeleteUser(id){
//	var user_info = GetUserInfo(id);
//	if(user_info)
//	bootbox.confirm({
//		message:'Bạn chắc chắn muốn xóa user này không?',
//		size:'small',
//		callback:function(result) {
//			if(result){
//				$.ajax({
//					url: "ajax/user_cmd.php",
//					dataType: "json",
//					//type:"POST",
//					data:{action:'delete',id:id,province_id:user_info.province_id,district_id:user_info.district_id,school_id:user_info.school_id},
//					success:function(data){
//						if(data.error===0) {
//							data.message='Xóa thành công';
//							setTimeout(function(){
//								LoadData(obj_search);
//							}, 1000);
//						}
//						bootbox.alert({
//							message:data.message,
//							size: 'small',
//							callback: function() {
//								if(data.error==5){
//									window.location.href='/login.php';
//								}
//							}
//						});
//					}
//				});
//			}
//		}
//	});
//	return false;
//}

function Fill_Popup(obj){
	if(obj){
		//modal_title.text('Sửa tin tức');
		tb_id_edit.val(obj._id);
		tb_username_edit.val(obj.username);
		tb_name_edit.val(obj.name);
		tb_email_edit.val(obj.email);

		if(obj.mobile) {
			tb_mobile_edit.val(obj.mobile).hide();
			tb_mobile_edit_show.val(util.hidenMobi(obj.mobile)).show();
		} else {
			tb_mobile_edit.val('').show();
			tb_mobile_edit_show.val('').hide();
		}

		if(obj.birthday!=null) {
			tb_birthday_edit.data('daterangepicker').setStartDate(Date2String(obj.birthday));
			tb_birthday_edit.data('daterangepicker').setEndDate(null);
		}
		else tb_birthday_edit.val('');
		ddl_province_edit.val(obj.province_id);
		LoadDataDistrictEdit(obj.province_id,obj.district_id);
		LoadDataSchoolEdit(obj.district_id,obj.school_id);
		tb_class_id_edit.val(obj.class_id);
		tb_class_name_edit.val(obj.class_name);
		cb_active_edit.prop('checked', obj.active).iCheck('update');
		tb_create_at_edit.val(Date2String(obj.created_at));
		tb_update_at_edit.val(Date2String(obj.updated_at));
	} else {
		//modal_title.text('Thêm mới tin tức');
		tb_id_edit.val('');
		tb_username_edit.val('');
		tb_name_edit.val('');
		tb_email_edit.val('');
		tb_mobile_edit.val('');
		tb_birthday_edit.val('');
		ddl_province_edit.val('0');
		ddl_district_edit.html('<option value="0">Chọn huyện</option>').val('0').prop('disabled',true);
		ddl_school_edit.html('<option value="0">Chọn trường</option>').val('0').prop('disabled',true);
		tb_class_id_edit.val('1');
		tb_class_name_edit.val('');
		tb_create_at_edit.val('');
		tb_update_at_edit.val('');
		cb_active_edit.prop('checked', false);
	}
	modal_message.html('');
	modal.modal('show');
}

function LoadDataDistrictEdit(id,select_id,ddl_district){
	var ddl = ddl_district || ddl_district_edit;
	$.ajax({
		url: "/ajax/district_cmd.php",
		type:"POST",
		dataType: "json",
		data:{action:'list',province_id:id},
		success: function(data) {
			//console.log(data);
			if (data.error == 0) {
				var html='<option value="0">Chọn huyện</option>';
				if(data.content && data.content.length>0)
					$.each(data.content,function(index,val){
						html+='<option value="' + val._id + '">' + val.name + '</option>';
					});
				ddl.html(html).prop('disabled',false);
				if(select_id && select_id>0) ddl.val(select_id);
			} else {
				Alert(data.message, function(){
					if (data.error == 5) window.location.href='/login.php';
				});
			}
		}
	});
}
//btn_save.removeAttr('disabled');
function LoadDataSchoolEdit(id,select_id){
	$.ajax({
		url: "/ajax/school_cmd.php",
		type:"POST",
		dataType: "json",
		data:{action:'list',district_id:id},
		success: function(data) {
			//console.log(data);
			if (data.error == 0) {
				var html='<option value="0">Chọn trường</option>';
				if(data.content && data.content.length>0)
					$.each(data.content,function(index,val){
						html+='<option value="' + val._id + '">' + val.name + '</option>';
					});
				ddl_school_edit.html(html).prop('disabled',false);
				if(select_id && select_id>0) ddl_school_edit.val(select_id);
			} else {
				Alert(data.message, function(){
					if (data.error == 5) window.location.href='/login.php';
				});
			}
		}
	});
}

function Save_Data(){
	var id = parseInt(tb_id_edit.val());
	var name = tb_name_edit.val();
	var email = tb_email_edit.val();
	var active = cb_active_edit.prop('checked');
	var mobile = tb_mobile_edit.val();
	var birthday = tb_birthday_edit.val();
	var province_id = ddl_province_edit.val();
	var province_name = ddl_province_edit.find('option:selected').text();
	var district_id = ddl_district_edit.val();
	var district_name = ddl_district_edit.find('option:selected').text();
	var school_id = ddl_school_edit.val();
	var school_name = ddl_school_edit.find('option:selected').text();
	var class_id = tb_class_id_edit.val();
	var class_name = tb_class_name_edit.val();

	if(mobile && mobile.length !== 10) {
		return Alert('Nghe nói SĐT giờ chỉ còn 10 số thôi thì phải!');
	}
	$.ajax({
		url: "/ajax/user_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'save',id:id,name:name,email:email,active:active,mobile:mobile,birthday:birthday,province_id:province_id,province_name:province_name,district_id:district_id,district_name:district_name,school_id:school_id,school_name:school_name,class_id:class_id,class_name:class_name},
		beforeSend: function( xhr ) {
			spinner.show();
			modal_message.show().text('Đang thao tác...');
		},
		success:function(data){
			if(data.error==0){
				LoadData(obj_search);
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

function ConfirmDeleteAvatar(id){
	var user_info = GetUserInfo(id);
	if(user_info)
		bootbox.confirm({
			message:'Bạn muốn xóa avatar của user này không?',
			size:'small',
			callback:function(result) {
				if(result){
					$.ajax({
						url: "/ajax/user_cmd.php",
						dataType: "json",
						type:"POST",
						data:{action:'delete_avatar',id:id},
						success:function(data){
							if(data.error===0) data.message='Xóa thành công';
							bootbox.alert({
								message:data.message,
								size: 'small',
								callback: function() {
									if(data.error==5){
										window.location.href='/login.php';
									}
								}
							});
						}
					});
				}
			}
		});
	return false;
}

function SaveChangePassword(){
	var id = parseInt(tb_id_pass_edit.val());
	var password = tb_password_edit.val();
	$.ajax({
		url: "/ajax/user_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'change_password',id:id,password:password},
		beforeSend: function( xhr ) {
			spinner.show();
			modal_message_password.show().text('Đang thao tác...');
		},
		success:function(data){
			if(data.error==0){
				modal_message_password.show().text('Update thành công');
				setTimeout(function(){
					modal_password.modal('hide');
				}, 1000);
			}
			else{
				modal_message_password.show().text(data.message);
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
		});
}

function FillModalPassword(id){
	var user_info = GetUserInfo(id);
	if(user_info){
		tb_id_pass_edit.val(user_info._id);
		tb_username_pass_edit.val(user_info.username);
		modal_message_password.text('');
		modal_password.modal('show');
		tb_password_edit.val('').focus();
	}
	return false;
}

function FillModalScore(id){
	var user_info = GetUserInfo(id);
	if(user_info){
		tb_id_score_edit.val(user_info._id);
		tb_username_score_edit.val(user_info.username);
		ddl_exam_type.find('option:eq(0)').prop('selected',true);
		modal_message_score.text('');
		modal_score.modal('show');
		current_user_id = id;
		LoadDataScore(id,user_info);
	}
	return false;
}

function ShowModalCache(id){
	var user_info = GetUserInfo(id);
	if(user_info){
		tb_id_score_cache.val(user_info._id);
		tb_username_score_cache.val(user_info.username);
		modal_cache.modal('show');
		modal_message_cache.text('');
		//current_user_id = id;
	}
	return false;
}

function FillModalPayment(id){
	var user_info = GetUserInfo(id);
	if(user_info){
		tb_id_payment_edit.val(user_info._id);
		tb_username_payment_edit.val(user_info.username);
		ddl_payment_done.find('option:eq(0)').prop('selected',true);
		modal_payment.modal('show');
		tbody_payment.html('');
		current_user_id = id;
		LoadDataPayment(id,0);
	}
	return false;
}

function FillModalVip(id){
	var user_info = GetUserInfo(id);
	if(user_info){
		tb_id_vip_edit.val(user_info._id);
		tb_username_vip_edit.val(user_info.username);
		tb_vip_day_edit.val(Date2String(user_info.vip_expire));
		tb_add_day_edit.val("");
		tb_add_day_note.val('');
		modal_vip.modal('show');
		modal_message_vip.text('');
		current_user_id = id;
	}
	return false;
}

function FillModalEmail(id){
    var user_info = GetUserInfo(id);
    if(user_info){
        tb_email_to.val(user_info.email? user_info.email: '');
        tb_email_subject.val('');
        // tb_email_body.val('');
        email_editor.setData('');
        lb_message_email.text('');
        modal_email.modal('show');
    }
    return false;
}

function FillModalSms(id){
    var user_info = GetUserInfo(id);
    if(user_info){
        tb_id_vip_edit.val(user_info._id);
        tb_username_vip_edit.val(user_info.username);
        tb_vip_day_edit.val(Date2String(user_info.vip_expire));
        tb_add_day_edit.val(1);
        tb_add_day_note.val('');
        modal_vip.modal('show');
        modal_message_vip.text('');
        current_user_id = id;
    }
    return false;
}

function LoadDataScore(user_id,user_info){
	if(!user_info) user_info = GetUserInfo(user_id);
	var type_id = ddl_exam_type.val();
	$.ajax({
		url: "/ajax/score_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'list',user_id:user_id,type_id:type_id,province_id:user_info.province_id,district_id:user_info.district_id,school_id:user_info.school_id},
		success:function(data){
			if(data.error==0){
				var html = '';
				if(data.content && data.content.length>0){
					$.each(data.content,function(index,val){
						html+='<tr>';
						html+='<td class="text-center">'+ val.round_id +'</td>';
						html+='<td class="text-center">'+ val.luot +'</td>';
						html+='<td class="text-center">'+ val.score +'</td>';
						html+='<td class="text-center">'+ val.time +'</td>';
						html+='<td class="text-center">'+ (val.code?val.code:'&nbsp;') +'</td>';
						html+='<td class="text-center">'+ Date2String(val.created_at) +'</td>';
						html+='<td class="text-center"><a href="javascript:void(0);" onclick="javascript:return ShowModalScoreDetail(' + user_id + ',' + val.round_id + ');">Chi tiết</a> | <a href="javascript:void(0);" tn-action="delete" onclick="javascript:return ConfirmDeleteScore(' + val._id + ',' + user_id + ',' + val.round_id + ');">Delete</a></td>';
						html+='</tr>';
					});
				}
				if(html=='') html='<tr><td class="text-center" colspan="7">Chưa có dữ liệu</td></tr>';
				tbody_score.html(html);
				if(data.rank_score_national!==false) rank_score_national.text('Xếp hạng toàn quốc: ' + (data.rank_score_national+1));
				else rank_score_national.text('Xếp hạng toàn quốc:...');
				if(data.rank_score_province!==false) rank_score_province.text('Xếp hạng tỉnh/thành phố: ' + (data.rank_score_province+1));
				else rank_score_province.text('Xếp hạng tỉnh/thành phố:...');
				if(data.rank_score_district!==false) rank_score_district.text('Xếp hạng quận/huyện: ' + (data.rank_score_district+1));
				else rank_score_district.text('Xếp hạng quận/huyện:...');
				if(data.rank_score_school!==false) rank_score_school.text('Xếp hạng trường: ' + (data.rank_score_school+1));
				else rank_score_school.text('Xếp hạng trường:...');
			}
			else{
				modal_message_score.show().text(data.message);
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
}

function LoadDataPayment(user_id,page_index){
	var done_status = ddl_payment_done.val();
	$.ajax({
		url: "/ajax/payment_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'list',user_id:user_id,page_index:page_index,done_status:done_status},
		success:function(data){
			if(data.error==0){
				payment_dataTables_info.text(GetDataInfo(data.row_count,data.page_size,data.page_index));
				payment_dataTables_paginate.html(GenPageJs(data.row_count,data.page_size,data.page_index,'pagination','active',5,'Payment_GoPage'));
				var html = '';
				if(data.content && data.content.length>0){
					$.each(data.content,function(index,val){
						html+='<tr>';
						if(val.res_body && val.res_body!=''){
							var arrbody = val.res_body.split(/\|/g);
							html+='<td class="text-center">'+ (arrbody[3]? arrbody[3]: val.card_number) +'</td>';
							html+='<td class="text-center">'+ (arrbody[4]? arrbody[4]: val.card_serial) +'</td>';
							html+='<td class="text-center">'+ (arrbody[5]? arrbody[5]: val.network) +'</td>';
							html+='<td class="text-center">'+ arrbody[0] +'</td>';
						}
						else{
							html+='<td class="text-center">'+ val.card_number +'</td>';
							html+='<td class="text-center">'+ val.card_serial +'</td>';
							html+='<td class="text-center">'+ val.network +'</td>';
							html+='<td class="text-center">&nbsp;</td>';
						}
						html+='<td class="text-center">'+ (val.amout?val.amout:'&nbsp;') +'</td>';
						html+='<td class="text-center">'+ Date2String(val.created_at) +'</td>';
						html+='</tr>';
					});
				} else {
					html = `<tr><td class="text-center" colspan="6">Không có dữ liệu, vui lòng kiểm tra <a href="javascript: void(0)" onclick="modal_payment.modal('hide'); showHistoryHandPayment(${user_id})">lịch sử cộng tay</a></td></tr>`;
				}
				tbody_payment.html(html);
			}
			else tbody_payment.html('');
		}
	});
}

function ConfirmDeleteScore(id, user_id, round_id) {
	bootbox.confirm({
		message:'Bạn chắc chắn muốn xóa không?',
		size:'small',
		callback: function(result) {
			if(result) {
				//kiểm tra bài thi
				$.ajax({
					url: "https://logs.trangnguyen.edu.vn/history/" + user_id + '/' + round_id,
					type: 'GET',
					dataType: "json",
					success: function (data) {
						if (data.error === 0) {
							var list = data.list;
							if(!list || list.length < 3) {
								execute()
							} else {
								if((!list[0].score && !list[0].time) || (!list[1].score && !list[1].time)  || (!list[2].score && !list[2].time)) {
									execute();
								} else {
									Alert('Không có bài nào điểm bất thường, nên đủ điều kiện để thi lại vòng: ' + round_id);
								}
							}
						} else {
							Alert(data.message);
						}
					}
				});

				var execute = function() {
					$.ajax({
						url: "/ajax/score_cmd.php",
						dataType: "json",
						type:"POST",
						data:{action: 'delete', id: id},
						success:function(data) {
							if(data.error === 0) {
								LoadDataScore(current_user_id);
								modal_message_score.show().text('Delete ok!');
							} else {
								modal_message_score.show().text(data.message);
								bootbox.alert({
									message:data.message,
									size: 'small',
									callback: function() {
										if(data.error === 5) {
											window.location.href='/login.php';
										}
									}
								});
							}
						}
					});
				}

			}
		}
	});
	return false;
}
function UpdateRankScore(user_id){
	var user_info = GetUserInfo(user_id);
	var type_id = ddl_exam_type.val();
	$.ajax({
		url: "/ajax/score_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'update_rank',user_id:user_id,type_id:type_id,province_id:user_info.province_id,district_id:user_info.district_id,school_id:user_info.school_id},
		success:function(data){
			if(data.error==0){
				modal_message_score.show().text('Update xếp hạng thành công');
				LoadDataScore(current_user_id);
			}
			else{
				modal_message_score.show().text(data.message);
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
}

function SaveVip(){
	var id = parseInt(tb_id_vip_edit.val());
	var money = tb_add_day_edit.val();
	var note = tb_add_day_note.val();
	$.ajax({
		url: "/ajax/user_cmd.php",
		type:'POST',
		dataType: "json",
		data:{action:'add_expire_vip', id: id, money: money, note: note},
		beforeSend: function( xhr ) {
			spinner.show();
			modal_message_vip.show().text('Đang thao tác...');
		},
		success:function(data) {
			if(data.error === 0) {
				LoadData(obj_search);
				modal_message_vip.show().text('Thêm thành công');
				setTimeout(function(){
					modal_vip.modal('hide');
				}, 1000);
			} else {
				modal_message_vip.show().text(data.message);
				if(data.error === 5) {
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
		});
}

function GoPage(page_index){
	obj_search.page_index = page_index;
	LoadData(obj_search);
}

function Payment_GoPage(page_index){
	LoadDataPayment(current_user_id,page_index);
}

function Set_Active(id, active){
	if(!active){

	}
}

function Set_Exam_Province(id,state){
	var user_info = GetUserInfo(id);
	state = state!=true;
	var msg='';
	if(state) msg = 'Cho phép ' + user_info.name + ' được phép thi tỉnh?';
	else msg = 'Bỏ thi tỉnh của ' + user_info.name + '?';
	Confirm(msg,function(result){
		if(result){
			$.ajax({
				url: "/ajax/user_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action:'exam_province',id:id,state:state},
				success:function(data){
					if(data.error==0){
						Alert('Update thành công');
						LoadData(obj_search);
					}
					else{
						Alert(data.message,function(){
							if(data.error==5){
								window.location.href='/login.php';
							}
						});
					}
				}
			});
		}
	});
	return false;
}

function Set_Exam_School(id, state) {
	var user_info = GetUserInfo(id);
	state = state!=true;
	var msg='';
	if(state) msg = 'Cho phép ' + user_info.name + ' được phép thi cấp trường?';
	else msg = 'Bỏ thi trường của ' + user_info.name + '?';
	Confirm(msg,function(result){
		if(result){
			$.ajax({
				url: "/ajax/user_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action:'exam_school',id:id,state:state},
				success:function(data){
					if(data.error === 0) {
						Alert('Update thành công');
						LoadData(obj_search);
					} else {
						Alert(data.message,function(){
							if(data.error==5){
								window.location.href='/login.php';
							}
						});
					}
				}
			});
		}
	});
	return false;
}

function Set_Exam_District(id,state) {
	var user_info = GetUserInfo(id);
	state = state!=true;
	var msg='';
	if(state) msg = 'Cho phép ' + user_info.name + ' được phép thi cấp huyện?';
	else msg = 'Bỏ thi huyện của ' + user_info.name + '?';
	Confirm(msg,function(result){
		if(result){
			$.ajax({
				url: "/ajax/user_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action:'exam_district',id:id,state:state},
				success:function(data){
					if(data.error === 0) {
						Alert('Update thành công');
						LoadData(obj_search);
					} else {
						Alert(data.message,function(){
							if(data.error === 5) {
								window.location.href='/login.php';
							}
						});
					}
				}
			});
		}
	});
	return false;
}

function Set_Exam_Province(id,state) {
	var user_info = GetUserInfo(id);
	state = state != true;
	var msg = '';
	if(state) msg = 'Cho phép ' + user_info.name + ' được phép thi cấp tỉnh?';
	else msg = 'Bỏ thi tỉnh của ' + user_info.name + '?';
	Confirm(msg,function(result) {
		if(result) {
			$.ajax({
				url: "/ajax/user_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action:'exam_province',id:id,state:state},
				success:function(data) {
					if(data.error === 0) {
						Alert('Update thành công');
						LoadData(obj_search);
					} else {
						Alert(data.message,function(){
							if(data.error === 5) {
								window.location.href='/login.php';
							}
						});
					}
				}
			});
		}
	});
	return false;
}

function Set_Exam_National(id, state) {
	var user_info = GetUserInfo(id);
	state = state != true;
	var msg='';
	if(state) msg = 'Cho phép ' + user_info.name + ' được phép thi Đình?';
	else msg = 'Bỏ thi Đình của ' + user_info.name + '?';
	Confirm(msg, function(result) {
		if(result) {
			$.ajax({
				url: "/ajax/user_cmd.php",
				type:'POST',
				dataType: "json",
				data:{action:'exam_national', id: id, state: state},
				success:function(data) {
					if(data.error === 0) {
						Alert('Update thành công');
						LoadData(obj_search);
					} else {
						Alert(data.message, function() {
							if(data.error === 5){
								window.location.href='/login.php';
							}
						});
					}
				}
			});
		}
	});
	return false;
}

function ShowModalScoreDetail(user_id, round_id) {
	// console.log(user_id, round_id);
	$.ajax({
		url: "https://logs.trangnguyen.edu.vn/history/" + user_id + '/' + round_id,
		type:'GET',
		dataType: "json",
		success:function(data) {
			if(data.error === 0) {
				$('#modal_exam_detail .user-id').text(user_id);
				$('#modal_exam_detail .round-id').text(round_id);

				var list = data.list;

				function fillTab(_info, tabId) {
					var tab = $('#modal_exam_detail #tab' + tabId);
					if(_info) {
						tab.find('.score').text(_info.score);
						tab.find('.time').text(util.Second2Minute(_info.time));
						tab.find('.game-name').text(mapGame[_info.game_id]? mapGame[_info.game_id]: '');
						var html = '';
						if(_info.answers_user && _info.answers_user.length > 0) {
							var startDate = new Date(_info.created_at);
							var endDate = new Date(_info.updated_at);
							tab.find('.created-at').text(util.date2String(startDate));
							tab.find('.updated-at').text(util.date2String(endDate));

							_info.answers_user.forEach((_i, i) => {
								html += '<tr>';
								html += '<td class="text-center">' + (i + 1) + '</td>';
								if(_info.game_id === 0) {
									if(_info.questions[i].type == '2') {
										html += '<td>' + _i.user + '</td>';
										html += '<td>' + _i.correct + '</td>';
										html += '<td class="text-center">HS Trả lời - Đáp án đúng (Tự luận)</td>';
									} else {
										html += '<td>' + _info.questions[i].answer[util.parseInt(_i.user)] + '</td>';
										html += '<td>' + _info.questions[i].answer[util.parseInt(_i.correct)] + '</td>';
										html += '<td class="text-center">HS Trả lời - Đáp án đúng (Trắc nghiệm)</td>';
									}
								} else if(_info.game_id === 1){
									html += '<td>' + (_info.questions.answers[_i.answer_index].content) + '</td>';
									html += '<td>' + (_info.questions.categories[_i.category_index].name) + '</td>';
									html += '<td class="text-center">HS Trả lời - Chủ đề đã chọn</td>';
								} else if(_info.game_id === 2) {
									html += '<td>' + _i.user + '</td>';
									html += '<td>' + _i.correct + '</td>';
									html += '<td class="text-center">Điền đáp án</td>';
								} else if(_info.game_id === 4) {
									html += '<td>' + (_info.questions[_i.index_1].content) + '</td>';
									html += '<td>' + (_info.questions[_i.index_2].content) + '</td>';
									html += '<td class="text-center">Cặp đã chọn</td>';
								} else {
									html += '<td></td>';
									html += '<td></td>';
									html += '<td></td>';
								}

								html += '<td class="text-center">' + (_i.is_correct? '<i class="fa fa-check text-green"></i>': '<span class="text-red">x</span>') + '</td>';
								html += '<td class="text-center">' + (_info.game_id === 0? Math.round((endDate - startDate)/1000): Math.round((new Date(_i.date) - startDate)/1000)) + '</td>';
								html += '</tr>';
							});
						} else {
							html = '<tr><td class="text-center" colspan="6">Không có dữ liệu</td></tr>'
						}
						tab.find('.tbody').html(html);
					} else {
						tab.find('.score').text('');
						tab.find('.time').text('');
						tab.find('.game-name').text('');
						tab.find('.created-at').text('');
						tab.find('.updated-at').text('');
						tab.find('.tbody').html('<tr><td class="text-center" colspan="6">Không có dữ liệu</td></tr>');
					}
				}

				if(list && list.length > 0) {
					var mapGame = {
						'0': 'Web',
						'1': 'chuột vàng',
						'2': 'Trâu vàng',
						'4': 'Mèo con'
					};

					var existTab = [];
					list.forEach(_info => {
						existTab.push(_info.test_id);
						fillTab(_info, _info.test_id);
					});
					for(var i = 1; i <= 3; i ++) {
						if(existTab.indexOf(i) < 0) fillTab(null, i);
					}
				} else {
					// clear data if null
					fillTab(null, 1);
					fillTab(null, 2);
					fillTab(null, 3);
				}

				modal_exam_detail.modal('show');
			} else {
				Alert(data.message);
			}
		}
	});
}

function showHistoryHandPayment(user_id) {
	var user_info = GetUserInfo(user_id);

	$.ajax({
		url: '/ajax/payment_log_cmd.php',
		type:'POST',
		data: { action: 'list', user_id },
		dataType: "json",
		success: function(data) {
			if(data.error === 0) {
				tb_id_payment_hand_edit.val(user_id);
				tb_username_payment_hand_edit.val(user_info.username);
				tb_payment_hand_expire.val(user_info.vip_expire? util.date2String(new Date(user_info.vip_expire * 1000)): '');

				var list = data.content;

				var html = '';
				if(list && list.length > 0) {
					list.forEach(function(_info, index) {
						html += '<tr>';
						html += `<td class="text-center">${index + 1}</td>`;
						html += `<td>${_info.user_admin}</td>`;
						html += `<td class="text-center">${_info.day}</td>`;
						html += `<td class="text-center">${_info.money? _info.money: ''}</td>`;
						html += `<td>${_info.note}</td>`;
						html += `<td class="text-center">${util.date2String(new Date(_info.created_at * 1000))}</td>`;
						html += '</tr>';
					});
				} else {
					html = `<tr><td class="text-center" colspan="6">Không có dữ liệu, vui lòng kiểm tra <a href="javascript: void(0)" onclick="modal_payment_hand_detail.modal('hide'); FillModalPayment(${user_id})">lịch sử nạp thẻ điện thoại</a></td></tr>`;
				}

				$('#modal_payment_hand_detail .tbody').html(html);

				modal_payment_hand_detail.modal('show');
			} else {
				Alert(data.message);
			}
		}
	});

	return false;
}

function addNoteVip(text) {
	if(text) {
		var value = tb_add_day_note.val().trim();
		if(value && !['.', ','].includes(text)) value += ' ';
		tb_add_day_note.val(value + text);
	} else {
		tb_add_day_note.val('');
	}
}