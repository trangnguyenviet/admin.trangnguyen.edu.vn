$(function(){
	//$('.number').ForceNumericOnly();
	$('body').addClass('sidebar-collapse');
	window.modal_detail = $('#modal_detail').modal({
		show: false,
		keyboard: false,
		backdrop: 'static'
	});
});

var app = angular.module("adminApp", ['ngCkeditor']);
app.controller("mainController", function($scope, $http) {
	$scope.class_id = '1';
	$scope.key_search = '';
	$scope.content = [];
	$scope.page_info = '';
	$scope.paginate = '';
	$scope.error = '';
	$scope.page_index = 0;
	$scope.isReady = true;
	$scope.modal = {};

	$scope.editorQuestion = {
		language: 'vi',
		height: '200px',
		removePlugins: 'basket'
	};
	$scope.editorTheAnswer = {
		language: 'vi',
		height: '200px'
	};
	// $scope.$on("ckeditor.ready", function(event) {
	//     $scope.isReady = true;
	// });

	$scope.getThumb = function(thumb){
		if(thumb) return thumb;
		return '/image/no_photo.jpg';
	};

	$scope.mongoDateShow = function(obj){
		if(obj){
			var sec = obj.sec;
			if(sec && typeof sec === 'number'){
				return util.date2String(new Date(sec*1000));
			}
		}
		return '';
	};

	$scope.add = function(){
		$scope.modal = {
			add: true,
			_id: 0,
			play: 100,
			name: '',
			time: 1800,
			content: [{
				type: 1,
				question: '',
				answered: '',
				answer: ['','','',''],
				the_answer: ''
			}]
		};
		modal_detail.modal('show');
	};

	$scope.edit = function(_id){
		var info = getInfoFromList(_id, $scope.content);
		if(info) {
			$http({
				method: "post",
				url: '/ajax/exam_answers_cmd.php',
				headers: {'Content-Type': 'application/json'},
				data: {action: 'info', id: _id}
			})
				.then(function(res) {
					var data = res.data;
					if(data){
						if(data.error==0){
							if(data.info){
								$scope.modal = data.info;
								modal_detail.modal('show');
							}
							else{
								Alert('Không tìm thấy thông tin bài thi');
							}
						}
						else{
							$scope.error = data.message;
						}
					}
				}, function(res) {
					$scope.error = 'Status error code: ' + res.status;
				});
		}
		else{
			Alert('Không tìm thấy thông tin bài thi');
		}
	};

	$scope.delete = function(id){
		Confirm('Bạn có muốn xóa bài thi này không?', function(result){
			if(result){
				$http({
					method: "post",
					url: '/ajax/exam_answers_cmd.php',
					headers: {'Content-Type': 'application/json'},
					data: {action: 'delete', id: id}
				})
					.then(function(res) {
						var data = res.data;
						if(data){
							if(data.error==0){
								LoadData();
								Alert('Xóa thành công!');
							}
							else{
								$scope.error = data.message;
							}
						}
					}, function(res) {
						$scope.error = 'Status error code: ' + res.status;
					});
			}
		});
	};

	$scope.deleteQuestion = function($index){
		Confirm('Bạn có muốn xóa bài thi này không?', function(result){
			if(result){
				if($scope.modal.content && $scope.modal.content.length>0){
					$scope.modal.content.splice($index,1);
					$scope.$apply();
				}
			}
		});
	};

	$scope.addQuestion = function($type){
		if(!$scope.modal.content) $scope.modal.content = [];
		var new_question = {
			type: $type,
			question: '',
			answered: '',
			the_answer: ''
		};
		if($type==1){
			new_question.answer = ['','','',''];
		}
		$scope.modal.content.push(new_question);
		$scope.modal.count = $scope.content.length;
	};

	$scope.save = function(){
		var data_post = {
			action: 'save',
			id: $scope.modal._id,
			name: $scope.modal.name,
			rewrite: write($scope.modal.name),
			class_id: $scope.class_id,
			play: $scope.modal.play,
			time: $scope.modal.time,
			content: $scope.modal.content
		};

		if(!data_post.name || data_post.name==''){
			Alert('Hãy nhập tên bài thi');
			return;
		}

		if(util.parseInt(data_post.play, 0) < 1){
			Alert('Hãy nhập số câu thi');
			return;
		}

		if(util.parseInt(data_post.time, 0) < 1){
			Alert('Hãy nhập thời gian thi');
			return;
		}

		for(var i = 0; i < data_post.content.length; i++ ){
			var question_raw = data_post.content[i];
			if(!question_raw.question || question_raw.question==''){
				Alert('Phải nhập tên bài thi, câu: ' + (i+1));
				return;
			}
			if(!question_raw.type) question_raw.type = 1;
			if(question_raw.type==1){
				for(var j=0; j < 4; j++){
					if(question_raw.answer[j] == ''){
						Alert('Phải nhập đầy đủ đáp án trả lời, câu: ' + (i+1));
						return;
					}
				}
			}
			if(!question_raw.answered || question_raw.answered==''){
				Alert('Phải chọn đáp án trả lời, câu: ' + (i+1));
				return;
			}
		}

		if(data_post.content.length < data_post.play){
			Alert('Số câu hỏi phải ≥ số câu thi. Số câu hỏi mới được ' + data_post.content.length + '/' + data_post.play + ' câu');
			return;
		}

		$scope.modal.error = 'Đang xử lý...';

		$http({
			method: "post",
			url: '/ajax/exam_answers_cmd.php',
			headers: {'Content-Type': 'application/json'},
			data: data_post
		})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						$scope._id = data.id;
						LoadData();
						setTimeout(function(){
							modal_detail.modal('hide');
						}, 1000);
						$scope.modal.error = '';
					}
					else{
						$scope.modal.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
	};

	var LoadData = function(){
		$scope.error = 'loading...';
		$http({
			method: "post",
			url: '/ajax/exam_answers_cmd.php',
			headers: {'Content-Type': 'application/json'},
			data: {action: 'list', search: $scope.key_search, page_index: $scope.page_index, class_id: $scope.class_id}
		})
			.then(function(res) {
				var data = res.data;
				if(data){
					if(data.error==0){
						$scope.content = data.content;
						$scope.error = '';
					}
					else{
						Alert(data.message,function(){
							if(data.error==5){
								window.location.href = '/login.php';
							}
						});
						$scope.error = data.message;
					}
				}
			}, function(res) {
				$scope.error = 'Status error code: ' + res.status;
			});
	};

	$scope.$watch('class_id', function (class_id) {
		$scope.page_index = 0;
		$scope.class_id = class_id;
		LoadData();
	});

	// $scope.back = function(){
	//     window.history.go(-1);
	// };
});