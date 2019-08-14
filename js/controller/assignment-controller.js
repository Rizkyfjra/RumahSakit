app.controller('AssignmentController', function($scope,$http){
	// deklarasi awal
	$scope.page = 1;
	$scope.state = 'list';
	$scope.showStatus = true;
	$scope.isLoading = true;
	
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}

		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});	
	var token = $_GET['token'];
		
	$scope.loadData = function(page){
	$http.get(baseUrl+'/api/list/assignment?token='+token+'&page='+page).success(function(response){
	
	$scope.data = response.data.result;
	if(!$scope.data)
	{
		$scope.state = 'error';
	}
	else
	{
		$scope.totalPage = response.data.totalPage;
		$scope.state = 'list';
		$scope.page = page;
		$scope.isLoading = false;
	}
	
	
	
	//console.log(response);
		});
	};
	
	$scope.loadData($scope.page);
	
	$scope.loadDetailData = function(assignment_id,type){
		if(type === undefined){
		type = ''; 
		}
		$scope.assignment_id = assignment_id;
		$http.get(baseUrl+'/api/view/assignment/'+assignment_id+'?type='+type+'&token='+token).success(function(response){
		
		$scope.state = 'detail';
		$scope.detailData = response.data.result;
	});
	};
	
	//fungsi-fungsi
	
	$scope.setShowStatus = function(setStatus){
		$scope.showStatus = setStatus;
	};
	
	$scope.getShowStatus = function(showStatus){
		return $scope.showStatus;
	};
	
	$scope.checkPrev = function(setPage){
		if(setPage <= 1)
		{
			return true;
		}
	};
	
	$scope.checkNext = function(setPage){
		if(setPage >= $scope.totalPage)
		{
			return true;
		}
	};
	
});