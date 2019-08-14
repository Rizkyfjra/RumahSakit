app.controller('NilaiController', function($scope,$http){	

	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}

		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});	
	var token = $_GET['token'];
	
	$scope.page = 1;
	$scope.type = $_GET['type'];
	
	if($_GET['type'] == 'tugas')
	{$scope.state = 'list_tugas';}
	else if($_GET['type'] == 'kuis')
	{$scope.state = 'list_kuis';}
	
	//$scope.state = 'list_tugas';
	$scope.isLoading = true;
	
	$scope.loadDetailData = function(id){
		$http.get(baseUrl+'/api/view/tugas/'+id+'?token='+token).success(function(response){
			$scope.detailData = response.data.result;
			$scope.hiyai = response.data.result;
			if(!$scope.detailData)
			{
				$scope.state = 'error';
			}
			else
			{	
				//alert($scope.detailData.nama_siswa);
				$scope.state = 'detail_tugas';
			}
		});
		
	};
	
	$scope.loadData = function(page,type){
		$http.get(baseUrl+'/api/list/nilai?type='+type+'&page='+page+'&token='+token).success(function(response){
		$scope.data = response.data.result;
		if(!$scope.data)
		{
			$scope.state = 'error';
		}
		else
		{
			$scope.totalPage = response.data.totalPage;
			$scope.isStudent = response.data.isStudent;
			$scope.page = page;
			$scope.isLoading = false;
			if(type == 'tugas')
			{$scope.state = 'list_tugas';}
			else if(type == 'kuis')
			{$scope.state = 'list_kuis';}
		}
	
			});
		};
	
	$scope.loadData($scope.page,$scope.type);
	
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