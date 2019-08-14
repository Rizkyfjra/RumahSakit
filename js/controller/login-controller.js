app.controller('LoginController', function($scope,$http){	
	$scope.page = 1;
	$scope.state = 'list';
	
	//alert(baseUrl);
	
	$scope.login = function(user,pass){
		var post_data = {
                username : user ,
                password : pass 
            };
        //alert(username);
		//
		//$http.get(baseUrl+'/api/list/lesson?token&page='+page).success(function(response){
		 $http.post(baseUrl+'/api/post/?model=login&type=angular',post_data).success(function(response){
		 $scope.data = response;
		 //alert($scope.data.status);
		 if($scope.data.error == 'TRUE')
		 {
			 alert('LOGIN ERROR');
		 }
		 if($scope.data.error == 'FALSE')
		 {
			 alert('LOGIN SUCCESS');
			 //window.location = baseUrl+'/mobile/assignment';
		 }
		
	});
		};
	

});