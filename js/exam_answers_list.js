/**
 * Created by tanmv on 12/12/2016.
 */

$(function(){
	//$('.number').ForceNumericOnly();

	$('.input-daterange input').each(function() {
		$(this).datepicker({
			format: "yyyy-mm-dd",
			autoclose: true,
			todayHighlight: true
		});
	});
});

var getInfoFromList = function(_id, list){
	if(_id && _id != '' && list && list.length>0){
		for(var i=0, info; info = list[i]; i++){
			if(info._id == _id) return info;
		}
	}
	return null;
};

var MsToDate = function($ms){
	if($ms && typeof $ms === 'number') return util.Ms2Date(new Date($ms));
	return '';
};

var app = angular.module("adminApp", []);
app.controller("mainController", function($scope, $http) {
	$scope._id = 0;
	$scope.name = '';
	$scope.date_from = '';
	$scope.date_to = '';
	$scope.list = [];

	$scope.MsToDate = function($ms){
		return MsToDate($ms);
	};

	$scope.add = function(){
		$scope._id = 0;
		$scope.name = '';
		$scope.date_from = '';
		$scope.date_to = '';
	};

	$scope.save = function(){
		var data_post = {
			action: 'save',
			_id: $scope._id,
			name: $scope.name,
			name_ko_dau: write($scope.name),
			date_from: (new Date($scope.date_from)).getTime(),
			date_to: (new Date($scope.date_to)).getTime()
		};
		$http({
			method: "post",
			url: '/ajax/exam_answers_list_cmd.php',
			headers: {'Content-Type': 'application/json'},
			data: data_post
		})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						$scope._id = data._id;
						LoadData();
					}
					$scope.error = data.message;
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
	};

	$scope.EditExam = function(type_id,class_id){
		window.location.href = '/exam/answers_edit.html?type_id='+type_id+'&class_id=' + class_id;
	};

	$scope.CopyExam = function(type_id,class_id){
		console.log(type_id,class_id);
	};

	$scope.DeleteExam = function(type_id,class_id){
		console.log(type_id,class_id);
	};

	$scope.EditType = function(type_id){
		console.log(type_id);
		var info = getInfoFromList(type_id, $scope.list);
		if(info){
			$scope._id = info._id;
			$scope.name = info.name;
			$scope.date_from = MsToDate(info.date_from);
			$scope.date_to = MsToDate(info.date_to);
			GoScrollTo('#input-detail');
		}
		else{
			Alert('Không tìm thấy thông tin bài thi');
		}

	};

	$scope.DeleteType = function(type_id){
		Confirm('Bạn có muốn xóa không?',function(result){
			if(result){
				$http({
					method: "POST",
					url: '/ajax/exam_answers_list_cmd.php',
					headers: {'Content-Type': 'application/json'},
					data: {action: 'delete', id: type_id}
				})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error==0){
								if($scope._id == type_id)
									$scope._id = 0;
								LoadData();
								Alert('Xóa thành công');
							}
							$scope.error = data.message;
						}
					}, function(res) {
						$scope.error = 'Status error code: ' + res.status;
					});
			}
		});
	};

	var LoadData = function(){
		$http({
			method: "POST",
			url: '/ajax/exam_answers_list_cmd.php',
			headers: {'Content-Type': 'application/json'},
			data: {action: 'list'}
		})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						var list = data.content;
						if(list && list.length>0){
							for(var i=0, type_info; type_info = list[i]; i++){

							}
						}
						LoadExam(function(content){
							//
						});
						$scope.list = list;
					}
					$scope.error = data.message;
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
	};

	var LoadExam = function(callback){
		$http({
			method: "POST",
			url: '/ajax/exam_answers_cmd.php',
			headers: {'Content-Type': 'application/json'},
			data: {action: 'list'}
		})
			.then(function(res) {
				var data = res.data;
				if(data){
					callback(data.content);
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
	};

	LoadData();
});