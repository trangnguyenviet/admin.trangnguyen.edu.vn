<section class="content-header">
	<h1>
		Sự kiện thi
		<small></small>
	</h1>
</section>
<div ng-app="adminApp" ng-controller="mainController">
	<section class="content">
		<div class="row" ng-repeat="event_info in event_list track by $index">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header">
						<h3 class="box-title text-info"><strong>{{'[' + event_info._id + '] ' + event_info.name}}</strong></h3>
					</div>
					<div class="box-body">
						<form class="form-horizontal">
							<div class="col-sm-5">
								<div class="form-group">
									<label class="col-sm-4 control-label">Thời gian bắt đầu:</label>
									<div class="col-sm-8">
										<input type="text" ng-value="SToDate(event_info.time_begin)" class="form-control input-sm" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label for="tb_id" class="col-sm-4 control-label">Thể loại thi:</label>
									<div class="col-sm-8">
										<!--<select ng-value="event_info.type" class="form-control" disabled>-->
										<!--	<option ng-repeat="tp in types" value="{{tp.id}}">{{tp.name}}</option>-->
										<!--</select>-->
										<input type="text" class="form-control" ng-value="getType(event_info.type)" disabled/>
									</div>
								</div>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label for="tb_name" class="col-sm-4 control-label">Thời gian kết thúc:</label>
									<div class="col-sm-8">
										<input type="text" ng-value="SToDate(event_info.time_end)" class="form-control input-sm" disabled/>
									</div>
								</div>
								<div class="form-group">
									<label for="tb_name" class="col-sm-4 control-label">Active:</label>
									<div class="col-sm-8">
										<input type="checkbox" ng-checked="event_info.active" disabled/>
									</div>
								</div>
							</div>
						</form>
						<div class="dataTables_wrapper form-inline dt-bootstrap news-data">
							<div class="row">
								<div class="col-sm-12">
									<table class="table table-bordered table-hover dataTable">
										<thead>
										<tr role="row">
											<th ng-repeat="c in classes" class="tnw-4">Lớp {{c}}</th>
										</tr>
										</thead>
										<tbody>
										<tr class="text-center">
											<td ng-repeat="c in classes">
												<div ng-show="event_info.exams && event_info.exams[c]">
														<div class="tn-exam-info">Câu hỏi: <strong>{{event_info.exams[c].content_count}}/{{event_info.exams[c].play}}</strong> | Thời gian: <strong>{{event_info.exams[c].time}}</strong>s</div>
														<div>Điểm mỗi câu: <strong>{{event_info.exams[c].spq? event_info.exams[c].spq: 10}}</strong></div>
														<div>
															<a href="javascript:void(0)" ng-click="testExam(c, event_info.exams[c], event_info)">Test</a> | <a href="javascript:void(0)" ng-click="downloadExam(c, event_info.exams[c], event_info)">Download</a> | <a href="javascript:void(0)" ng-click="editExam(c, event_info.exams[c], event_info)">Sửa</a> | <a href="javascript:void(0)" ng-click="copyExam(c, event_info.exams[c], event_info)">Copy</a> | <a href="javascript:void(0)" ng-click="deleteExam(c, event_info.exams[c], event_info)">Xóa</a>
														</div>
													</div>
												</div>
												<div ng-show="!event_info.exams || !event_info.exams[c]">
													<div class="tn-exam-info">(Chưa có nội dung)</div>
													<div>
														<a href="javascript:void(0)" ng-click="editExam(c, null, event_info)">Sửa</a> | <a href="javascript:void(0)" onclick="Alert('Chưa có nội dung')">Copy</a> | <a href="javascript:void(0)" onclick="Alert('Chưa có nội dung')">Xóa</a>
													</div>
												</div>
											</td>
										</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="row text-center">
								<button type="button" ng-click="editEvent(event_info)" class="btn btn-warning"><i class="fa fa-pencil-square-o"></i> Sửa</button>
								<button type="button" ng-click="deleteEvent(event_info)" class="btn btn-danger"><i class="fa fa-remove"></i> Xóa</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<button class="btn btn-lg btn-success" ng-click="addEvent()" type="button"><i class="fa fa-plus"></i> Thêm sự kiện</button>
			</div>
		</div>
	</section>

	<div id="modal-detail" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator" ng-submit="saveEvent()">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info" ng-show="event_info.isAdd"><strong>Thêm sự kiện</strong></h4>
						<h4 class="modal-title text-info" ng-show="!event_info.isAdd"><strong>Sửa sự kiện</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="form-group" ng-show="!event_info.isAdd">
								<label class="col-sm-3 control-label">ID:</label>
								<div class="col-sm-7">
									<input type="text" ng-model="event_info._id" class="form-control" placeholder="(server tự sinh)" disabled/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Tên sự kiện:</label>
								<div class="col-sm-7">
									<input type="text" ng-model="event_info.name" class="form-control" placeholder="Tên sự kiện" required/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Thời gian bắt đầu:</label>
								<div class="col-sm-7">
									<input type="text" ng-model="event_info.time_from" class="form-control date-range" placeholder="Thời gian bắt đầu">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Thời gian kết thúc:</label>
								<div class="col-sm-7">
									<input type="text" ng-model="event_info.time_to" class="form-control date-range" placeholder="Thời gian kết thúc">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Thể loại thi:</label>
								<div class="col-sm-7">
									<select ng-model="event_info.type" class="form-control">
										<option value="0">Chọn thể loại</option>
										<option ng-repeat="tp in types" value="{{tp.id}}" ng-disabled="!tp.enabled">{{tp.name}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Active:</label>
								<div class="col-sm-7">
									<input type="checkbox" ng-model="event_info.active"/>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-sm-8 message">
								<label class="modal-message">{{msgEvent}}</label>
							</div>
							<div class="col-sm-4 button">
								<button type="submit" class="btn btn-info"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu</button>
								<button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Đóng</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div id="modal_copy" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<form role="form" class="form-horizontal" data-toggle="validator" ng-submit="execCopy()">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title text-info"><strong>Copy đề thi</strong></h4>
					</div>
					<div class="modal-body">
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-3 control-label">Tên sự kiện:</label>
								<div class="col-sm-7">
									<select class="form-control" ng-model="exam_copy.type_copy">
										<option value="0">Hãy chọn sự kiện</option>
										<!--<option ng-repeat="event_info in event_list" value="{{event_info._id}}" ng-disabled="exam_copy.disable_type == event_info._id">{{event_info.name}}</option>-->
										<option ng-repeat="event_info in event_list" value="{{event_info._id}}">{{event_info.name}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Lớp:</label>
								<div class="col-sm-7">
									<select ng-model="exam_copy.class_id_copy" class="form-control">
										<option value="1">Lớp 1</option>
										<option value="2">Lớp 2</option>
										<option value="3">Lớp 3</option>
										<option value="4">Lớp 4</option>
										<option value="5">Lớp 5</option>
										<option value="6">Lớp 6</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-sm-8 message">
								<label class="modal-message"></label>
							</div>
							<div class="col-sm-4 button">
								<button type="submit" class="btn btn-info"><i class="fa fa-files-o" aria-hidden="true"></i> Copy</button>
								<button type="button" class="btn btn-info" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Đóng</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="https://rawgit.com/SheetJS/js-xlsx/master/shim.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script type="text/javascript" src="https://rawgit.com/eligrey/Blob.js/master/Blob.js"></script>
<!--<script type="text/javascript" src="https://rawgit.com/eligrey/FileSaver.js/master/FileSaver.js"></script>-->
<script type="text/javascript" src="/plugins/file-saver/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/swfobject/2.2/swfobject.min.js"></script>
<script src="/js/downloadify.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Base64/1.0.1/base64.min.js"></script>
<script>
	$(function(){
		window.modal_detail = $('#modal-detail').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		window.modal_copy = $('#modal_copy').modal({
			show: false,
			keyboard: false,
			backdrop: 'static'
		});

		$('.date-range').daterangepicker({
			"singleDatePicker": true,
			"showDropdowns": true,
			"timePicker": true,
			"timePicker24Hour": true,
			"alwaysShowCalendars": true,
			"autoApply": true,
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
	});

	var MsToDate = function($ms){
		if($ms && typeof $ms === 'number') return util.date2String(new Date($ms));
		return '';
	};

	var app = angular.module("adminApp", []);
	app.controller("mainController", function($scope, $http) {
		$scope.event_list = [];
		$scope.classes = [1,2,3,4,5,6];

		$scope.types = [
			{id: 1, name: 'Thi tự do ( không lưu điểm)', enabled: true},
			{id: 2, name: 'Thi tự do + lưu điểm cuối cùng', enabled: true},
			{id: 3, name: 'Thi tự do + lưu điểm cao nhất', enabled: true},
			{id: 4, name: 'Thi tự do + Max điểm-dừng + lưu điểm cuối', enabled: true},
			{id: 5, name: 'Thi 1 lần + lưu điểm', enabled: true},
			{id: 6, name: 'Thi bằng mã + lưu điểm', enabled: false},
			{id: 7, name: 'Nộp học phí + lưu điểm', enabled: false},
			{id: 8, name: 'Đạt số điểm... + lưu điểm', enabled: false},
			{id: 9, name: 'Qua vòng... + lưu điểm', enabled: false}
		];

		$scope.getType = function(id){
			var info = $scope.types.filter(function(i){
				return i.id == id;
			})[0];
			return info? info.name: '';
		};

		$scope.SToDate = function($s){
			if($s && typeof $s === 'number'){
				return MsToDate($s*1000);
			}
			return '';
		};

		$scope.addEvent = function(){
			$scope.event_info = {
				isAdd: true,
				_id: 0,
				name: '',
				time_from: '',
				time_to: '',
				type: '0',
				msgEvent: '',
				active: true
			};
			window.modal_detail.modal('show');
		};
		$scope.editEvent = function(event_info){
			var info = cloneObject(event_info);
			info.type = info.type? info.type.toString(): '0';
			info.time_from = info.time_begin>0? MsToDate(info.time_begin * 1000): '';
			info.time_to = info.time_end>0? MsToDate(info.time_end * 1000): '';
			$scope.event_info = info;
			window.modal_detail.modal('show');
		};

		$scope.deleteEvent = function(event_info){
			Confirm('Bạn muốn xóa sự kiện: ' + event_info.name + '?',function(result){
				if(result){
					$http({
						method: "post",
						url: '/ajax/exam_event_type_cmd.php',
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						data: $.param({action:'delete', id: event_info._id})
					})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error==0){
								ShowMessError('Delete thành công');
								loadData();
							}
							else{
								Alert(data.message);
							}
						}
					}, function(res) {
						Alert('ERROR: ' + res.status);
					});
				}
			});
		};
		$scope.saveEvent = function(){
			var dataPost = {
				action:'save',
				id: $scope.event_info._id,
				name: $scope.event_info.name,
				name_ko_dau: write($scope.event_info.name),
				type: $scope.event_info.type,
				active: true
			};
			if($scope.event_info.time_from != '' && $scope.event_info.time_to != ''){
				try{
					dataPost.time_begin = $scope.event_info.time_from!=''? $scope.event_info.time_from: null;
					dataPost.time_end = $scope.event_info.time_to!=''? $scope.event_info.time_to: null;
				}
				catch(e){
					Alert(e);
					dataPost.begin_use = 0;
					dataPost.end_use = 0;
				}
			}
			$http({
				method: "post",
				url: '/ajax/exam_event_type_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param(dataPost)
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						ShowMessError('lưu thành công');
						loadData();
						setTimeout(function(){
							window.modal_detail.modal('hide');
						},1000);
					}
					else{
						Alert(data.message);
					}
				}
			}, function(res) {
				Alert('ERROR: ' + res.status);
			});
		};

		$scope.testExam = function(c, examInfo, eventInfo){
			Alert('chờ đợi là hạnh phúc!<br/>(^_^)');
		};

        $scope.downloadExam = function(c, examInfo, eventInfo) {
            $http({
                method: "post",
                url: '/ajax/exam_event_cmd.php',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                data: $.param({action: 'info', id: examInfo._id})
            })
            .then(function(res) {
                var data = res.data;
                if(data){
                    if(data.error === 0){
                        var type = 'xlsx';
                        function s2ab(s) {
                            if(typeof ArrayBuffer !== 'undefined') {
                                var buf = new ArrayBuffer(s.length);
                                var view = new Uint8Array(buf);
                                for (var i=0; i!=s.length; ++i) view[i] = s.charCodeAt(i) & 0xFF;
                                return buf;
                            } else {
                                var buf = new Array(s.length);
                                for (var i=0; i!=s.length; ++i) buf[i] = s.charCodeAt(i) & 0xFF;
                                return buf;
                            }
                        }

                        var content = data.info.content;
                        content = content.reduce(function(arr, q, i){
                            var obj = {
                                stt: (i+1),
                                type: q.type,
                                cauhoi: q.question
                            };
                            if(q.type==1){
                                for(var j=0; j<4; j++){
                                    obj['dapan' + (j + 1)] = q.answer[j];
                                }
                                obj.traloi = util.parseInt(data.info.answers[i]) + 1;
                            }
                            else{
                                obj.traloi = data.info.answers[i];
                            }
                            obj.goiy = q.the_answer? q.the_answer: '';
                            arr.push(obj);
                            return arr;
                        },[]);
                        var filename = eventInfo.name_ko_dau + '_lop-' + c;
                        var wb = {
                            SheetNames:['Sheet1'],
                            Sheets: {
                                Sheet1: XLSX.utils.json_to_sheet(content)
                            }
                        };
                        var wbout = XLSX.write(wb, {bookType:type, bookSST:true, type: 'binary'});
                        try {
                            saveAs(new Blob([s2ab(wbout)],{type:"application/octet-stream"}), filename + '.' + type);
                        } catch(e) {
                            if(typeof console !== 'undefined') console.log(e, wbout);
                        }
                    }
                    else{
                        Alert(data.message);
                    }
                }
            }, function(res) {
                Alert('ERROR: ' + res.status);
            });
        };

        $scope.editExam = function(c, examInfo, eventInfo){
			window.location.href='/exam/event_edit.html?type_id=' + eventInfo._id + '&class_id=' + c + (examInfo?'&id='+examInfo._id:'');
		};

		$scope.copyExam = function(c, examInfo, eventInfo){
			var exam = cloneObject(examInfo);
			exam.class_id_copy = c.toString();
			exam.type_copy = '0';
			//exam.disable_type = eventInfo._id;
			$scope.exam_copy = exam;
			modal_copy.modal('show');
		};

		$scope.execCopy = function(){
			var type_copy_id = parseInt($scope.exam_copy.type_copy);
			var class_copy_id = parseInt($scope.exam_copy.class_id_copy);

			if(type_copy_id == 0){
				Alert('hãy chọn sự kiện');
				return;
			}
			if(class_copy_id == 0){
				Alert('hãy chọn lớp');
				return;
			}
			if(type_copy_id == $scope.exam_copy.type && class_copy_id == $scope.exam_copy.class_id){
				Alert('Không thể copy chính nó!');
				return;
			}

			$http({
				method: "post",
				url: '/ajax/exam_event_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action: 'copy', id: $scope.exam_copy._id, exam_event_id: type_copy_id, class_id: class_copy_id})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						ShowMessError('Copy thành công');
						loadData();
						setTimeout(function(){
							window.modal_copy.modal('hide');
						},1000);
					}
					else{
						Alert(data.message);
					}
				}
			}, function(res) {
				Alert('ERROR: ' + res.status);
			});
		};
		$scope.deleteExam = function(c, examInfo, eventInfo){
			Confirm('<strong>Bạn muốn xóa bài thi này không?</strong><br/>(sau khi xóa sẽ không lấy được lại đề)',function(result){
				if(result){
					$http({
						method: "post",
						url: '/ajax/exam_event_cmd.php',
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						data: $.param({action:'delete', id: examInfo._id})
					})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error==0){
								ShowMessError('Xóa thành công');
								loadData();
							}
							else{
								Alert(data.message);
							}
						}
					}, function(res) {
						Alert('ERROR: ' + res.status);
					});
				}
			});
		};

		var loadExam = function(types){
			$http({
				method: "post",
				url: '/ajax/exam_event_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action:'list', types: types})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						var listEvent = {};
						$scope.event_list.forEach(function(eventInfo){
							eventInfo.exams = {};
							listEvent[eventInfo._id] = eventInfo;
						});
						data.content.forEach(function(info){
							var eventInfo = listEvent[info.type_id];
							if(eventInfo){
								eventInfo.exams[info.class_id] = info;
							}
						});
					}
					else{
						Alert(data.message);
					}
				}
			}, function(res) {
				Alert('ERROR: ' + res.status);
			});
		};

		var loadData = function(){
			$http({
				method: "post",
				url: '/ajax/exam_event_type_cmd.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: $.param({action:'list'})
			})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						$scope.event_list = data.content;
						loadExam(data.content.map(function(info){return info._id;}));
					}
					else{
						Alert(data.message);
					}
				}
			}, function(res) {
				Alert('ERROR: ' + res.status);
			});
		};

		loadData();
	});
</script>