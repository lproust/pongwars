<html ng-app="pongwars">
<head>
	<title>Pong Wars</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="container" ng-controller="newMatchCtrl">
		
		<div class="center-content">
			<img src="img/logo-pongwars.png">
		</div>
		<div class="center-content">
			<p>Beta v0.2</p>
			<img src="img/separateur1.png">
		</div>
		<br>
		<div ng-controller="newUserCtrl">
			<div class="text-center">
				<a href="#" ng-show="!newUser" ng-click="newUser=!newUser">Règles / Inscription</a>
			</div>
			<div class="pongwars-rules hero-unit text-center" ng-show="newUser">
				<i class="button-close-newUser icon-remove-sign" ng-click="newUser=!newUser"></i>
				<h2>Règles officielles PongWars</h2>
				<ul>
					<li>Classement individuel basé sur l'algorithme du <a href="http://fr.wikipedia.org/wiki/Classement_Elo">classement Elo</a></li>
					<li>Tout nouveau joueur commence avec un classement de 1000</li>
					<li>Match en 11 points gagnants et deux points d'écarts</li>
					<li>Le gagnant du match rentre le score sur PongWars</li>
				</ul>
				<br>
				<form ng-submit="saveNewUser()">
					<input ng-model="newUserName" name="newUserName" type="text" placeholder="Prénom / Nom">
					<br>
					<input class="btn" ng-class="{'btn-success':btnSubmit.success==true, 'btn-danger':btnSubmit.error==true}" type="submit" value="{{btnSubmit.value}}">
				</form>
			</div>
		</div>
		<div class="hero-unit">
			<form ng-submit="saveNewMatch()">
				<div class="row-fluid">
					<div class="span5 offset2">
						<h3>Player 1</h3>
						<select ng-model="player1" ng-options="user.name for user in users">
							<option value="">---</option>
						</select>
						<br>
						<input class="score-input" type="text" placeholder="0" ng-model="player1_score">
						<p ng-show="predictions.player1">{{predictions.player1}}</p>	
					</div>
					<div class="span2">
						<h3>Player 2</h3>
						<select ng-model="player2" ng-options="user.name for user in users">
							<option value="">---</option>
						</select>
						<br>
						<input class="score-input" type="text" placeholder="0" ng-model="player2_score">
						<p ng-show="predictions.player2">{{predictions.player2}}</p>
					</div>
				</div>
				<div class="center-content">
					<input type="submit" class="btn btn-save-match"></button>
				</div>
				<div class="center-content">
					<img src="img/pucepongwars6.png">
				</div>
			</form>
		</div>

		<div class="scores-ribbon center-content">
			<img src="img/scores.png">
		</div>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Joueur</th>
					<th>Classement</th>
				</tr>
			</thead>
			<tbody ng-repeat="user in users">
				<tr>
					<td>{{user.name}}</td>
					<td>{{user.points}}</td>
				</tr>
			</tbody>
		</table>
		<!--
		<div class="center-content">
			<img src="img/battle.png">
		</div>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Vainqueur</th>
					<th>Perdant</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody ng-repeat="match in matchs">
				<tr>
					<td>{{getName(match.winner_id)}} - {{match.winner_score}}</td>
					<td>{{getName(match.loser_id)}} - {{match.loser_score}}</td>
					<td>{{match.created_at}}</td>
				</tr>
			</tbody>
		</table>
		-->
		<div class="center-content powered-ribbon">
			<p>Bug, idée d'amélioration ? <a href="mailto:alexandre.mouillard@ubixr.com">alexandre.mouillard@ubixr.com</a></p>
			<a href="http://www.vigisys.fr"><img src="img/poweredbyvigisys.png"></a>
		</div>
		<!--
		<form ng-submit="saveNewUser()">
			M'inscrire : <input ng-model="newUser" type="text">
			<input type="submit">
		</form>
		<div class="hero-unit" ng-click="setFirst()">
			<h1>Pong Wars</h1>
				<p>Règles officielles du pong wars : </p>
			<br>
			<form ng-submit="saveNewMatch()" class="form-horizontal">
				<div class="span6 offset2">
					<select ng-model="player1" ng-options="user.name for user in users">
					</select>
					<select ng-model="player2" ng-options="user.name for user in users">
					</select>
					<input type="text" ng-model="player1_score">
					<input type="text" ng-model="player2_score">
					<input type="submit">
				</div>
			</form>
		</div>

		<h1>Classement joueurs</h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Joueur</th>
					<th>Classement</th>
				</tr>
			</thead>
			<tbody ng-repeat="user in users">
				<tr>
					<td>{{user.name}}</td>
					<td>{{user.points}}</td>
				</tr>
			</tbody>
		</table>

		<h1>Matchs récents</h1>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Vainqueur</th>
					<th>Perdant</th>
					<th>Date</th>
				</tr>
			</thead>
			<tbody ng-repeat="match in matchs">
				<tr>
					<td>{{getName(match.winner_id)}} - {{match.winner_score}}</td>
					<td>{{getName(match.loser_id)}} - {{match.loser_score}}</td>
					<td>{{match.created_at}}</td>
				</tr>
			</tbody>
		</table>
		-->
	</div>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>
