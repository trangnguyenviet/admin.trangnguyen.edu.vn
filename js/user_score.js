var result_obj,obj_search={};
var SCORE;
$(function(){
	$('body').addClass('sidebar-collapse');
	SCORE = {
		controls:{
			ddl_province: $('#ddl_province'),
			ddl_district: $('#ddl_district'),
			ddl_school: $('#ddl_school'),
			ddl_class: $('#ddl_class'),
			tb_limit: $('#tb_limit'),
			tb_round:$('#tb_round'),
			bt_search: $('#bt_search')
		},
		content:{
			div: $('#content_info'),
			tbody: $('#content_info .tbody')
		}
	};
	
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
	//end modal score

	//event
	SCORE.controls.ddl_province.change(function(){
		var province_id = $(this).val();
		if(province_id>0) {
			LoadDataDistrict(province_id);
		}
		else SCORE.controls.ddl_district.val(0).prop('disabled',true).html('<option value="0">Tất cả huyện</option>');
		SCORE.controls.ddl_school.val(0).prop('disabled',true);
	});
	SCORE.controls.ddl_district.change(function(){
		var district_id = Number(SCORE.controls.ddl_district.val());
		if(district_id>0) {
			LoadDataSchool(district_id);
		}
		else SCORE.controls.ddl_school.val(0).prop('disabled',true).html('<option value="0">Tất cả trường</option>');
	});
	SCORE.controls.ddl_school.change(function(){
		//
	});
	//end event
	
	SCORE.controls.bt_search.click(function(){
		var province = SCORE.controls.ddl_province.val(),
		district = SCORE.controls.ddl_district.val(),
		school = SCORE.controls.ddl_school.val(),
		class_id = SCORE.controls.ddl_class.val(),
		round = SCORE.controls.tb_round.val(),
		limit= SCORE.controls.tb_limit.val(); 
		
		$.ajax({
			url: "/ajax/user_cmd.php",
			type:"POST",
			dataType: "json",
			data:{action:'top-score',province:province,district:district,school:school,class_id:class_id,limit:limit,round:round},
			success: function(data) {
				if (data.error == 0) {
					FillData(data.content);
				} else {
					Alert(data.message, function(){
						if (data.error == 5) window.location.href='/login.php';
					});
				}
			}
		});
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
			if (data.error == 0) {
				var html='<option value="0">Tất cả huyện</option>';
				if(data.content && data.content.length>0)
				$.each(data.content,function(index,val){
					html+='<option value="' + val._id + '">' + val.name + '</option>';
				});
				SCORE.controls.ddl_district.html(html).prop('disabled',false);
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
			if (data.error == 0) {
				var html='<option value="0">Tất cả trường</option>';
				if(data.content && data.content.length>0)
				$.each(data.content,function(index,val){
					html+='<option value="' + val._id + '">' + val.name + '</option>';
				});
				SCORE.controls.ddl_school.html(html).prop('disabled',false);
			} else {
				Alert(data.message, function(){
					if (data.error == 5) window.location.href='/login.php';
				});
			}
		}
	});
}

function FillData(content){
	var html='';
	result_obj = content;
	if(content){
		$.each(content,function(i,val){
			html+='<tr>';
			html+='<td class="text-center">'+(i+1)+'</td>';
			html+='<td class="text-center">'+ val._id +'</td>';
			html+='<td>'+ val.username +'</td>';
			html+='<td>'+ val.name +'</td>';
			html+='<td class="text-center">'+ (val.birthday?Date2String2(val.birthday):'&nbsp;') +'</td>';
			html+='<td class="text-center">'+ val.class_id +'</td>';
			html+='<td class="text-center">' + val.class_name +'</td>';
			html+='<td title="'+val.district_name+' - '+ val.province_name +'">' + val.school_name +'</td>';
			html+='<td class="text-center"><a href="#" onclick="FillModalScore('+val._id+')">' + val.total_score_4 + '</a></td>';
			html+='<td class="text-center">' + val.total_time_4 + '</td>';
			html+='<td class="text-center">' + (val.current_round_4?val.current_round_4:'&nbsp;') + '</td>';
			html+='</tr>';
		});
	}
	SCORE.content.tbody.html(html);
	SCORE.content.div.show();
}

function GetUserInfo(id){
	if(result_obj!=null && result_obj.length>0){
		for(var i=0;i<result_obj.length;i++){
			var val = result_obj[i];
			if(val._id==id){
				return val;
				break;
			}
		}
	}
	return null;
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
						html+='<td class="text-center">'+ Date2String(val.created_at) +'</td>';
						html+='<td class="text-center"><a href="javascript:void(0);" tn-action="delete" onclick="javascript:return ConfirmDeleteScore(' + val._id + ');">Delete</a></td>';
						html+='</tr>';
					});
				}
				if(html=='') html='<tr><td class="text-center" colspan="6">Chưa có dữ liệu</td></tr>'; 
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
