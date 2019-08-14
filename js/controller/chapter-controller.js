app.controller('ChapterController', function($scope,$http){	
	$scope.page = 1;
	$scope.state = 'list';
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
		$http.get(baseUrl+'/api/list/chapter?token='+token+'&page='+page).success(function(response){
		
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
			});
		};
	$scope.loadData($scope.page);
	
	$scope.loadDetailChapterData = function(chapter_id){
		$http.get(baseUrl+'/api/view/chapter/'+chapter_id+'?token='+token).success(function(response){
		
		$scope.detailChapterData = response.data.result;
		$scope.state = 'detail_chapter';
		$scope.chapter_id = chapter_id;
	});
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
		
		
	//alert($_GET['token']);
	// var $_GET = {};
	// document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		// function decode(s) {
			// return decodeURIComponent(s.split("+").join(" "));
		// }

		// $_GET[decode(arguments[1])] = decode(arguments[2]);
	// });



});