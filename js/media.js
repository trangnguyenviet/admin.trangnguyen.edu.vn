var MEDIA = {
	GetInfo: function(url,callback){
		MEDIA.GetVideoInfo(url,function(data){
			if(callback && typeof callback === "function"){
				var data_response = {};
				if(data.content){
					data_response.error = 0;
					data_response.message = 'ok';
					var content = data.content;
					var duration = Math.floor(content.format.duration);
					data_response.duration_view=MEDIA.SecToTime(duration);
					data_response.duration_second=duration;
					data_response.format_name=content.format.format_name;
					data_response.bitrate=content.format.bit_rate;
					
					var streams = content.streams;
					for(var i=0;i<streams.length;i++){
						var stream = streams[i];
						if(stream.codec_type=='video'){
							data_response.codec_name=stream.codec_name;
							data_response.width=stream.width;
							data_response.height=stream.height;
							break;
						}
					}
				}
				else{
					data_response.error = 1;
					data_response.message = 'video không tồn tại';
				}
				callback(data_response);
			}
			else console.log('MEDIA: not have callback!');
		});
	},
	GetVideoInfo: function(url,callback){
		$.ajax({
			url: "/ajax/media_cmd.php",
			data:{action:'get_info',file:url},
			dataType: "json",
			success: function(data){
				if(data.content && data.content!=''){
					//nothing
				}
				else{
					data.content=null;
				}
				if(data.content==[]) data.content=null;
				if(callback && typeof callback === "function") callback(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log('error',jqXHR,textStatus,errorThrown);
			}
		});
	},
	SecToTime: function(totalSec){
		var hours = Math.floor(totalSec / 3600);
		var minutes = Math.floor((totalSec-hours*3600) / 60);
		var seconds = totalSec-hours*3600-minutes*60;
		if(hours>0){
			return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
		}
		else{
			return (minutes < 10 ? "0" + minutes : minutes) + ":" + (seconds  < 10 ? "0" + seconds : seconds);
		}
	}
};