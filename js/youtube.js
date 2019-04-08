var Youtube = {
	GetInfo: function(url,callback){
		Youtube.GetVideoYoutubeInfo(url, function(data){
			if(callback && typeof callback === "function"){
				var data_response = {};
				if (!data.error) {
					if(data.items.length > 0) {
						var item = data.items[0];
						if (item.snippet.channelId === 'UCbPjl5UW7MPB58xmLIgC6VA') {
							data_response.duration_view = Youtube.convertISO8601ForView(item.contentDetails.duration);
							data_response.duration_second = Youtube.convertISO8601ToSeconds(item.contentDetails.duration);
							data_response.title = item.snippet.title;
							data_response.tags = item.snippet.tags;
							data_response.description = item.snippet.description;
							data_response.thumb = item.snippet.thumbnails.medium.url;
						} else {
							if(window.Alert) window.Alert('<span class="text-red">Không hỗ trợ kênh Youtube này!</span><br/>Hãy upload lên kênh<br/><a href="https://www.youtube.com/channel/UCbPjl5UW7MPB58xmLIgC6VA" target="_blank"><strong>Tiếng Việt Trạng Nguyên</strong></a>');
							else alert('Không hỗ trợ kênh Youtube này!');

							data_response.error = 2;
							data_response.message = 'Không hỗ trợ kênh Youtube này';
						}
					} else {
						data_response.error = 1;
						data_response.message = 'video không tồn tại';
					}
				} else {
					data_response.error = data.error.code;
					data_response.message = data.error.message;
				}
				callback(data_response);
			} else console.log('Youtube: not have callback!');
		});
	},
	GetVideoYoutubeInfo: function(url,callback){
		var yt_video_id = Youtube.getYoutubeIdByUrl(url);
		if(yt_video_id){
			$.ajax({
				url: "https://www.googleapis.com/youtube/v3/videos?id=" + yt_video_id + "&key=AIzaSyDyqtMpYJNTlU1JO2YlSKBaaaV4aBk0kxM&fields=items(id,contentDetails(duration),snippet(title,channelId,thumbnails(medium),description,tags),statistics)&part=snippet,contentDetails,statistics",
				dataType: "jsonp",
				success: function(data){
					if(callback && typeof callback === "function") callback(data);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					alert (textStatus, + ' | ' + errorThrown);
				}
			});
		}
		else{
			return {error:'video id not validate'};
		}
	},
	getYoutubeIdByUrl: function(url) {
		var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
		var match = url.match(regExp);
		if(match && match[7].length == 11) {
			return match[7];
		}
		return false;
	},
	formatSecondsAsTime: function(secs) {
		var hr = Math.floor(secs / 3600),
		min = Math.floor((secs - (hr * 3600)) / 60),
		sec = Math.floor(secs - (hr * 3600) - (min * 60));

		if (hr < 10) {
			hr = "0" + hr;
		}
		if (min < 10) {
			min = "0" + min;
		}
		if (sec < 10) {
			sec = "0" + sec;
		}
		if (hr) {
			hr = "00";
		}
		return hr + ':' + min + ':' + sec;
	},
	convertISO8601ToSeconds: function(input) {
		var reptms = /^PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?$/;
		var hours = 0, minutes = 0, seconds = 0, totalseconds;
		if (reptms.test(input)) {
			var matches = reptms.exec(input);
			if (matches[1]) hours = Number(matches[1]);
			if (matches[2]) minutes = Number(matches[2]);
			if (matches[3]) seconds = Number(matches[3]);
			totalseconds = hours * 3600  + minutes * 60 + seconds;
		}
		return totalseconds;
	},
	convertISO8601ForView: function(input) {
		var reptms = /^PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?$/;
		var hours = 0, minutes = 0, seconds = 0, view;
		if (reptms.test(input)) {
			var matches = reptms.exec(input);
			if (matches[1]) hours = matches[1];
			if (matches[2]) minutes = matches[2];
			if (matches[3]) seconds = matches[3];
			view = hours + ':' + minutes + ':' + seconds;
		}
		return view;
	}
};