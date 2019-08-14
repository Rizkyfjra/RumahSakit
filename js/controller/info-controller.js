app.controller('InfoController', ['$scope','$http', 'PDFViewerService',function($scope,$http,pdf){	
	$scope.page = 1;
	$scope.mode = 'info';
	$scope.controller_name = 'InfoController';
	$scope.state = 'list';
	$scope.role = 'assignment';
	$scope.BASEURL = baseUrl;
	
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}

		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});	
	var token = $_GET['token'];
	//alert($scope.mode);
	$scope.token = token;
	
	$scope.loadData = function(page,type){
		switch(type)
		{
			case 'quiz' :
			$scope.link = baseUrl+'/api/list/quiz?token='+token+'&page='+page;
			break;
			
			case 'assignment' :
			$scope.link = baseUrl+'/api/list/assignment?token='+token+'&page='+page;
			break;
		}
		
		$http.get($scope.link).success(function(response){
		$scope.data = response.data.result;
		
		if(!$scope.data)
		{
			$scope.state = 'error';
		}
		else
		{
			$scope.totalPage = response.data.totalPage;
			$scope.page = page;
			switch(type)
			{
				case 'quiz' :
				$scope.role = 'quiz';
				break;
				
				case 'assignment' :
				$scope.role = 'assignment';
				break;
			}
		}
		$scope.state = 'list';
			});
		};
		
	$scope.fade = true;
	$scope.setFade = function(cond){
    $scope.fade = cond;
  };
	
	
	$scope.loadData($scope.page,'assignment');
	
	//load assignment detail data
	$scope.loadDetailAssignmentData = function(assignment_id,type){
		if(type === undefined){
		type = ''; 
		}
		//$scope.assignment_id = assignment_id;
		$http.get(baseUrl+'/api/view/assignment/'+assignment_id+'?type='+type+'&token='+token).success(function(response){
		
		$scope.state = 'detail_assignment';
		$scope.detailAssignmentData = response.data.result;
		$scope.assignment_id = assignment_id;
		
		
	});
	};
	
	//load quiz detail data
	$scope.loadDetailQuizData = function(quiz_id){
		 $http.get(baseUrl+'/api/view/quiz/'+quiz_id+'?token='+token).success(function(response){
		 $scope.detailQuizData = response.data.result;
		 $scope.state = 'detail_quiz';
		 $scope.quiz_id = quiz_id;
	});
	};
	
	$scope.StartQuiz = function(quiz_id)
	{
		 $http.get(baseUrl+'/api/view/startQuiz/'+quiz_id+'?token='+token).success(function(response){
		 $scope.StartQuizData = response.data.result;
		 $scope.state = 'startquiz';
		 $scope.quiz_id = quiz_id;
		 
		 $scope.zzz = 'step1';
	  	 $scope.nav = 1;
		 //alert($scope.nav);
		 $scope.asas = [];
		 $scope.totalSoal = response.data.result.total_question;
		 $scope.isLastSoal = false;		 
		 $scope.isFirstSoal = true;
		 
		 });
	};
	
	$scope.NextSoal = function()
	{
		$scope.nav = $scope.nav + 1;
		$scope.zzz = 'step'+$scope.nav;
		
		if($scope.nav >= $scope.totalSoal)
		{
			$scope.isLastSoal = true;
			$scope.isFirstSoal = false;
		}
		if($scope.nav <= 1)
		{
			$scope.isFirstSoal = true;
			$scope.isLastSoal = false;
		}
		//alert($scope.nav+'\n IsFirstSoal :'+$scope.isFirstSoal+'\n IsLastSoal :'+$scope.isLastSoal);
	}
	
	$scope.PrevSoal = function()
	{
		$scope.nav = $scope.nav - 1;
		$scope.zzz = 'step'+$scope.nav;
		
		if($scope.nav >= $scope.totalSoal)
		{
			$scope.isLastSoal = true;
			$scope.isFirstSoal = false;
		}
		if($scope.nav <= 1)
		{
			$scope.isFirstSoal = true;
			$scope.isLastSoal = false;
		}
		//alert($scope.nav+'\n IsFirstSoal :'+$scope.isFirstSoal+'\n IsLastSoal :'+$scope.isLastSoal);
	}
	
	$scope.SetSoal = function(set)
	{
		
		$scope.nav = set;
		$scope.zzz = 'step'+$scope.nav;
		
		if($scope.nav >= $scope.totalSoal)
		{
			$scope.isLastSoal = true;
			$scope.isFirstSoal = false;
		}
		if($scope.nav <= 1)
		{
			$scope.isLastSoal = false;
			$scope.isFirstSoal = true;
		}
		//alert($scope.nav+'\n IsFirstSoal :'+$scope.isFirstSoal+'\n IsLastSoal :'+$scope.isLastSoal);
	}	
	
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

}]);