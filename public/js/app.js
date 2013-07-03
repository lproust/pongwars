var app = angular.module('pongwars', []);

app.controller('newMatchCtrl', function($scope, $http, getNameFilter){

	/*           API           */
	$scope.error = {};
	$scope.newUser = true;
	$scope.listUsers = function(){
		$http({
			method: 'GET',
			url: 'users'
		}).success(function(data){
			$scope.users = data;
		});
	}

	$scope.setFirst = function(){
		$scope.user = $scope.users[0];
	}

	$scope.listMatchs = function(){
		$http({
			method: 'GET',
			url: 'matchs'
		}).success(function(data){
			$scope.matchs = data;
		});
	}

	$scope.getName = function getName(id) {
      return getNameFilter($scope.users, id);
    };

    $scope.$watch('[player1, player2, player1_score, player2_score] | json', function(data){
    	$scope.predictions = {};

    	data = JSON.parse(data);
    	if(data[0] && data[1]){
    		var newMatch= {
    			player1_id: $scope.player1.id,
    			player2_id: $scope.player2.id,
    			player1_score: $scope.player1_score,
    			player2_score: $scope.player2_score
    		};

    		$http({
    			method: 'POST',
    			url: 'matchPrediction',
    			data: newMatch
    		}).success(function(data){
    			$scope.predictions.player1 = data.player1_ratings_prediction;
    			$scope.predictions.player2 = data.player2_ratings_prediction;
    		})
    	}
    });

	$scope.saveNewMatch = function(){
		var newMatch = {
			player1_id: $scope.player1.id,
			player2_id: $scope.player2.id,
			player1_score: $scope.player1_score,
			player2_score: $scope.player2_score
		};

		$http({
			method: 'POST',
			url: 'matchs',
			data: newMatch
		}).success(function(){
			$scope.listUsers();
			$scope.listMatchs();
			$scope.predictions = {};
			$scope.player1_score = null;
			$scope.player2_score = null;
		});
	}

	$scope.listUsers();
	$scope.listMatchs();
});

app.filter('getName', ['filterFilter', function (filterFilter) {
    return function (input, id) {
      var r = filterFilter(input, {id: id})[0];
      return r && r.name;
    };
}]);

app.controller('newUserCtrl', function($scope, $http){
	$scope.btnSubmit = {value: "Ok, je m'inscris !"};

	$scope.saveNewUser = function(name){
	var newUserName = {
		name: $scope.newUserName
	};

	$http({
		method: 'POST',
		url: 'users',
		data: newUserName
	}).success(function(data){
		$scope.listUsers();
		$scope.newUserName = '';
			if(data.error){
				$scope.btnSubmit.success = false;
				$scope.btnSubmit.error = true;
				$scope.btnSubmit.value = 'Erreur : ce nom est déjà pris';
			}
			else{
				$scope.btnSubmit.error = false;
				$scope.btnSubmit.success = true;
				$scope.btnSubmit.value = 'Inscrit avec succès';
			}
	});
	}
});