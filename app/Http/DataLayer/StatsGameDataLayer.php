<?php

namespace App\Http\DataLayer;

use App\Model\Games;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsGameDataLayer
{
    /**
     * Return count of games and ended games of user
     *
     * @param  var
     * @return  array
     */
    public function getGamesCount($user_id)
    {
        $user=User::find($user_id);
        $user_games = $user->games;
        $ended_games = $user->endedgames;
        $all_games_count = $user_games->count();
        $ended_games_count = $ended_games->count();
        $ended_ratio = $ended_games_count == 0 ? 0 : round($all_games_count/$ended_games_count, 3);
        return ['total'=>$all_games_count,'ended'=>$ended_games_count];
    }

    /**
     * Return (attempt_count, sum of attempts times, game score) for each game for user
     *
     * @param  var
     * @return  array
     */
    public function getUserAttempts($user_id)
    {
        $collection=User::join('user_games','user_id','=','users.id')
            ->join('game_attempts','user_games.id','=','user_game_id')
            ->select( DB::raw('count(*) as attempt_count'), DB::raw('sum(attempt_time) as sum_attempts_times'), DB::raw('count(*) * sum(attempt_time) as game_score'))
            ->where('users.id',$user_id)
            ->groupBy('user_game_id')
            ->get();
        return $collection;
    }

    /**
     * Return (best, worst, average) time game for user
     *
     * @param  var
     * @return  array
     */
    public function getAttemptsTimeData($collection, $ended_games_count)
    {
        $best_time = collect($collection)->min('sum_attempts_times');
        !isset($best_time) ? $best_time = 0 :'';
        $worst_time = collect($collection)->max('sum_attempts_times');
        !isset($worst_time) ? $worst_time = 0 :'';
        $average_time = $ended_games_count == 0 ? 0 : round(($best_time + $worst_time)/$ended_games_count, 3);
        return ['average'=>$average_time,'best'=>$best_time,'worst'=>$worst_time];
    }
    /**
     * Return (best, worst, average) no game for user
     *
     * @param  var
     * @return  array
     */
    public function getAttemptsNoData($collection, $ended_games_count)
    {
        $best_no = collect($collection)->min('attempt_count');
        !isset($best_no) ? $best_no = 0 :'';
        $worst_no = collect($collection)->max('attempt_count');
        !isset($worst_no) ? $worst_no = 0 :'';
        $average_no = $ended_games_count == 0 ? 0 : round(($best_no + $worst_no)/$ended_games_count);
        return ['average'=>$average_no,'best'=>$best_no,'worst'=>$worst_no];
    }
    /**
     * Return comparing between user and other players
     *
     * @param  var
     * @return  array
     */
    public function getRank($collection,$user_id)
    {
        $users_end_games=Games::where('ended',1)->pluck('user_id')->toArray();
        $users_ended_games=array_unique($users_end_games);

        $collection_users=User::join('user_games','user_id','=','users.id')
            ->join('game_attempts','user_games.id','=','user_game_id')
            ->select('users.id', DB::raw('count(*) as attempt_count'), DB::raw('sum(attempt_time) as sum_attempts_times'), DB::raw('count(*) * sum(attempt_time) as game_score'))
            ->where('users.id','!=',$user_id)
            ->groupBy('user_game_id')
            ->get();


        $game_score=collect($collection)->min('game_score');
        $data=collect($collection_users)->pluck('game_score','id')->where('game_score','<',$game_score)->groupBy('id');
        $count=$data->count();
        $rank=$count + 1;
        return ['me'=>$rank,'total'=>count($users_ended_games)];
    }
}
