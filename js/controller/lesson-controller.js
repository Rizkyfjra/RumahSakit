app.directive('fileInput', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, elm, attrs) {
            //var model = $parse(attrs.fileModel);
            //var modelSetter = model.assign;
            elm.bind('change', function(){
				$parse(attrs.fileInput)
				.assign(scope,elm[0].files)
				scope.$apply()
            });
        }
    };
}]);

app.controller('LessonController', ['$scope','$http', 'PDFViewerService',function($scope,$http,pdf){	
	$scope.page = 1;
	$scope.mode = 'lesson';
	$scope.controller_name = 'LessonController';
	$scope.state = 'list';
	$scope.isLoading = true;
	$scope.BASEURL = baseUrl;
	
	$scope.viewer = pdf.Instance("viewer");

    $scope.nextPage = function() {
        $scope.viewer.nextPage();
    };

    $scope.prevPage = function() {
        $scope.viewer.prevPage();
    };

    $scope.pageLoaded = function(curPage, totalPages) {
        $scope.currentPage = curPage;
        $scope.totalPages = totalPages;
    };
	
	$scope.getPdfLink = function (lesson_id,file) {
	return baseUrl+'/images/chapters/'+lesson_id+'/'+file;
	};
	
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
		function decode(s) {
			return decodeURIComponent(s.split("+").join(" "));
		}

		$_GET[decode(arguments[1])] = decode(arguments[2]);
	});	
	var token = $_GET['token'];
	//alert(token);

$scope.filesChanged	 = function(elm){
	$scope.files = elm.files;
	$scope.$apply;
}

$scope.model = [];

$scope.upload = function(model){
	var post_data = {
    jawaban : model.jawaban,
    state : model.state,
    assignment_id : $scope.assignment_id
	};
	//alert(model.jawaban);
		
	$http.post(baseUrl+'/api/post/?model=student_assignment_form', post_data).success(function(x){
			alert('input sukses');
			console.log(x);
        });
		
	if($scope.files)
	{	
		//alert('file upload status = ok');
		var fd = new FormData();
		angular.forEach($scope.files,function(file){
        fd.append('file', file);
		})
		//$http.post(baseUrl+'/api/TestUpload', fd,
		$http.post(baseUrl+'/api/post/?model=student_assignment_file', fd,
		{
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(d){
			alert('upload success');
			console.log(d);
        });
	}	
		
    };			
	 
	$scope.loadData = function(page){
		$http.get(baseUrl+'/api/list/lesson?token='+token+'&page='+page).success(function(response){
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
		
	$scope.fade = true;
	$scope.setFade = function(cond){
    $scope.fade = cond;
  };
	
	
	$scope.loadData($scope.page);
	
	$scope.loadDetailData = function(lesson_id,type){
		if(type === undefined){
		type = 'materi'; 
		}
		$scope.lesson_id = lesson_id;
		$http.get(baseUrl+'/api/view/lesson/'+lesson_id+'?type='+type+'&token='+token).success(function(response){
		
		$scope.state = 'detail';
		$scope.detailData = response.data.result;
	});
	};
	
	//load chapter detail data
	$scope.loadDetailChapterData = function(chapter_id){
		$http.get(baseUrl+'/api/view/chapter/'+chapter_id+'?token='+token).success(function(response){
		
		$scope.detailChapterData = response.data.result;
		var b = response.data.result;
		$scope.state = 'detail_chapter';
		$scope.chapter_id = chapter_id;
		$scope.interface = {};
		
		$scope.$on('$videoReady', function videoReady() {
            $scope.interface.options.setAutoplay(true);
            $scope.interface.sources.add(baseUrl+'/images/chapters/'+b.lesson_id+'/'+b.chapterFiles_files);
        });
		
	});
	};
	
	
	//load assignment detail data
	$scope.loadDetailAssignmentData = function(assignment_id,type){
		if(type === undefined){
		type = ''; 
		}
		$scope.assignment_id = assignment_id;
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
	
	//load lks detail data
	$scope.loadDetailLksData = function(lks_id){
		//alert(lks_id);
		 $http.get(baseUrl+'/api/view/lks/'+lks_id+'?token='+token).success(function(response){
		 $scope.detailLksData = response.data.result;
		 $scope.state = 'detail_lks';
		 $scope.lks_id = lks_id;
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
	
	$scope.submitQuiz = function(){
	var post_data =[];
	
	if(confirm("Apakah anda yakin akan menyelesaikan ulangan ?"))
	{
	
	post_data = 
		{	
			jawaban : $scope.asas,
			quiz_id : $scope.quiz_id
		};	
	
	//alert(JSON.stringify(post_data));
	// var post_data = [
	// {
      // id : user ,
      // password : pass
	// },
	
	 $http.post(baseUrl+'/api/post/?model=studentquiz&token='+token,post_data).success(function(response){
			$scope.loadDetailData($scope.lesson_id,'materi');
	 });
	}
		}
	
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
		
	
		
	
	$scope.checkPrevSoal = function(setPage){
		if(setPage <= 1)
		{
			return true;
		}
	};
	
	$scope.checkNextSoal = function(setPage){
		if(setPage >= $scope.totalSoal)
		{
			return true;
		}
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

}]);