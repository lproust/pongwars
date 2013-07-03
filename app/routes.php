<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('application');
});

Route::get('users', function(){
	return User::orderBy('points', 'desc')->get();
});

Route::post('matchPrediction', function(){
	$player1_id = Input::get('player1_id');
 	$player2_id = Input::get('player2_id');
 	$player1_score = Input::get('player1_score');
 	$player2_score = Input::get('player2_score');

 	if($player1_id == $player2_id){
 		return Response::json(["error" => "Le match doit opposer deux joueurs différents !"]);
 	}

	if(isset($player1_id, $player2_id, $player1_score, $player2_score))
	{
		if($player1_score > $player2_score)
		{
			$winner_id = $player1_id; $loser_id = $player2_id;
			$winner_score = $player1_score; $loser_score = $player2_score;
		}
		else
		{
			$winner_id = $player2_id; $loser_id = $player1_id;
			$winner_score = $player2_score; $loser_score = $player1_score;
		}
		$winner = User::find($winner_id);
		$loser = User::find($loser_id);
		$elo = new Rating($winner->points, $loser->points, 1, 0);
		$newRatings = $elo->getNewRatings();
		if($winner_id == $player1_id){
			$newPlayer1Score = round($newRatings['a'], 0);
			$deltaA = $newPlayer1Score - $winner->points;
			$newPlayer2Score = round($newRatings['b'], 0);
			$deltaB = $newPlayer2Score - $loser->points;
			return Response::json([
			"player1_ratings_prediction" => "$newPlayer1Score (+$deltaA)",
			"player2_ratings_prediction" => "$newPlayer2Score ($deltaB)"
		]);
		}
		elseif($winner_id == $player2_id){
			$newPlayer2Score = round($newRatings['a'], 0);
			$newPlayer1Score = round($newRatings['b'], 0);
			$deltaA = $newPlayer2Score - $winner->points;
			$deltaB = $newPlayer1Score - $loser->points;
			return Response::json([
			"player1_ratings_prediction" => "Classement : $newPlayer1Score ($deltaB)",
			"player2_ratings_prediction" => "Classement : $newPlayer2Score (+$deltaA)"
		]);
		}
		
		//return $newRatings;
	}
	else
	{
		return Response::json(["error" => "player1_id / player2_id / player1_score / player2_score doivent être définis !"]);
	}
});

Route::get('matchs', function(){
	$matchs = Match::orderBy('created_at', 'desc')->get();
	foreach($matchs as $match){
		$match->winner_name = User::find($match->winner_id)->name;
		$match->loser_name = User::find($match->loser_id)->name;
	}
	return $matchs;
});

Route::post('users', function(){
	if(User::where('name', '=', Input::get('name'))->first()){
		return Response::json(["error" => "Oops, ce nom de joueur est déjà utilisé !"]);
	}
	elseif(Input::get('name') == ""){
		return Response::json(["error" => "Oops, impossible de s'inscrire sans entrer de nom !"]);
	}
	else{
		$user = User::create([
			"name" => Input::get('name'),
			"points" => 1000
		]);
		return $user->id;
	}
});

Route::post('matchs', function(){
	$player1_id = Input::get('player1_id');
	$player2_id = Input::get('player2_id');
	$player1_score = Input::get('player1_score');
	$player2_score = Input::get('player2_score');

	if($player1_score > $player2_score){
		$winner_id = $player1_id; $loser_id = $player2_id;
		$winner_score = $player1_score; $loser_score = $player2_score;
	}
	else{
		$winner_id = $player2_id; $loser_id = $player1_id;
		$winner_score = $player2_score; $loser_score = $player1_score;
	}

	if($winner_score > 11 && $loser_score < 11) $winner_score = 11;
	if($winner_score > 11 && $loser_score > 11) {$winner_score = 11; $loser_score = 9;}

	$match = Match::create([
		"winner_id" => $winner_id,
		"loser_id" => $loser_id,
		"winner_score" => $winner_score,
		"loser_score" => $loser_score
	]);

	$winner = User::find($winner_id);
	$loser = User::find($loser_id);
	$elo = new Rating($winner->points, $loser->points, 1, 0);
	$newRatings = $elo->getNewRatings();

	$winner->points = $newRatings['a'];
	$winner->save();
	$loser->points = $newRatings['b'];
	$loser->save();
});

Route::get('recalculatePoints', function(){
	Match::recalculatePoints();
});
