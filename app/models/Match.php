<?php
class Match extends Eloquent
{
	protected $table = 'matchs';
	protected $guarded = ['id'];

	public static function recalculatePoints(){
		$matchs = self::orderBy('created_at', 'asc')->get();
		DB::table('users')->update(['points' => 1000]);
		foreach($matchs as $match){
			$winner = User::find($match->winner_id);
			$loser = User::find($match->loser_id);
			$elo = new Rating($winner->points, $loser->points, 1, 0);
			$newRatings = $elo->getNewRatings();

			$winner->points = $newRatings['a'];
			$winner->save();
			$loser->points = $newRatings['b'];
			$loser->save();
		}
	}
}