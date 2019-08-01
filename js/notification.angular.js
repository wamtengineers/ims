angular.module('notification', ['ngAnimate', 'angularMoment']).controller('notificationController', 
	function ($scope, $http, $interval, $filter	) {
		$scope.students = [];
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctRequest = {
				method: 'POST',
				url: 'notification_manage.php',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function(obj) {
					var str = [];
					for(var p in obj){
						str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
					}
					return str.join("&");
				},
				data: wctData
			}
			$http(wctRequest).then(function(wctResponse){
				wctCallback(wctResponse.data);
			}, function () {
				console.log("Error in fetching data");
			});
		}
		$scope.get_students = function( type ) {
			$scope.wctAJAX( {tab: 'student_list', id: $("#id").val(), type: type}, function( response ){
				$scope.students = response;
			});
		}
		$scope.get_students(0)
	}
)