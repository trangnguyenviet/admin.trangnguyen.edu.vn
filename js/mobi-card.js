var MobileCard;
$(function(){
	MobileCard={
		control: {
			tb_number: $('#tb_number'),
			bt_search: $('#bt_search')
		},
		content: {
			div: $('#content_info'),
			tbody: $('#content_info .tbody')
		}
	};
	MobileCard.LoadData=function(){
		var cardnumber = MobileCard.control.tb_number.val();
		if(cardnumber==''){
			Alert('Hãy nhập mã thẻ',function(){
				setTimeout(function(){
					MobileCard.control.tb_number.focus();
				},200);
			});
			return;
		}
		$.ajax({
			url: "/ajax/payment_cmd.php",
			type:'POST',
			dataType: "json",
			data:{action:'search',number:cardnumber},
			success:function(data){
				if(data.error==0){
					var html = '';
					if(data.content && data.content.length>0){
						$.each(data.content,function(index,val){
							var arrbody = val.res_body?val.res_body.split(/\|/g):null;
							html+='<tr>';
							html+='<td class="text-center">'+ val.user_id +'</td>';
							html+='<td class="text-center">'+ val.form.pin_card +'</td>';
							html+='<td class="text-center">'+ val.form.card_serial +'</td>';
							html+='<td class="text-center">'+ val.form.type_card +'</td>';
							html+='<td class="text-center">'+ (val.amout?val.amout:'&nbsp;') +'</td>';
							html+='<td class="text-center">'+ (arrbody?arrbody[0]:'') +'</td>';
							html+='<td class="text-center">'+ Date2String(val.created_at) +'</td>';
							html+='</tr>';
						});
					}
					else html = '<tr><td class="text-center" colspan="7">Không có dữ liệu</td></tr>';
					MobileCard.content.tbody.html(html);
					MobileCard.content.div.show();
				}
				else MobileCard.content.tbody.html('<tr><td class="text-center" colspan="7">'+data.message+'</td></tr>');
			}
		});
	};
	
	MobileCard.control.bt_search.click(function(){
		MobileCard.LoadData();
	});
});