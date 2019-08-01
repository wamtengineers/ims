angular.module('chalan', ['ngAnimate', 'angularMoment']).controller('chalanReceiving', 
	function ($scope, $http, $interval, $filter	) {
		$scope.chalans = [];
		$scope.confirm_amount = false;
		$scope.amount = 0;
		$scope.chalan_id = "";
		$scope.wctAJAX = function( wctData, wctCallback ) {
			wctRequest = {
				method: 'POST',
				url: 'fees_chalan_manage.php',
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
		$scope.addChalanID = function( e ) {
			if( e.which == 13 && $scope.chalan_id != '' ) {
				$scope.addReceving( "", $scope.chalan_id );
			}
		}
		$scope.addReceving = function( barcode, id ){
			var data = {tab: 'receiving'};
			if( typeof id === 'undefined' ) {
				data.barcode = barcode;
			}
			else{
				data.id = id;
			}
			if( $scope.confirm_amount ) {
				data.do_confirm = 1;
			}
			$scope.wctAJAX( data, function( response ){
				if( $scope.confirm_amount && response.status == 1 ) {
					$scope.amount = response.amount;
					$scope.amount = prompt( "Enter Receiving amount.", response.amount );
					$scope.wctAJAX( {tab: 'receiving', id: response.id, do_confirm: 1, amount: $scope.amount}, function( response ){
						$scope.chalans.push( response );
					});
				}
				else{
					$scope.chalans.push( response );
				}
			});
		}
		$scope.deleteReceiving = function( id, pos ){
			$scope.chalans[ pos ].is_deleted = 1;
			$scope.wctAJAX( {tab: 'receiving', receiving_id: id}, function( response ){
					
			});
		}
	}
)
$(document).ready(function(){
	if( $("#chalanReceiving").length > 0 ) {
		$(document).scannerDetection({
			onComplete: function(barcode, qty){
				angular.element($("#chalanReceiving")).scope().addReceving(barcode);
			}
		});
	}
});