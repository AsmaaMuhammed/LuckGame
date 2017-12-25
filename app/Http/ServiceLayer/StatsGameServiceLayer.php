<?php

namespace App\Http\ServiceLayer;

use App\Http\DataLayer\StatsGameDataLayer;
use Illuminate\Http\Request;

class StatsGameServiceLayer
{
    /**
     * Return count of games and ended games of user
     *
     * @param  var
     * @return  array
     */
    public function getGamesCount($user_id)
    {
        $stats_data_layer_obj=new StatsGameDataLayer();
        $data = $stats_data_layer_obj->getGamesCount($user_id);
        return $data;
    }

    /**
     * Return (attempt_count, sum of attempts times, game score) for each game for user
     *
     * @param  var
     * @return  array
     */
    public function getUserAttempts($user_id)
    {
        $stats_data_layer_obj=new StatsGameDataLayer();
        $collection=$stats_data_layer_obj->getUserAttempts($user_id);
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
        $stats_data_layer_obj=new StatsGameDataLayer();
        $data = $stats_data_layer_obj->getAttemptsTimeData($collection, $ended_games_count);
        return $data;
    }

    /**
     * Return (best, worst, average) no game for user
     *
     * @param  var
     * @return  array
     */
    public function getAttemptsNoData($collection, $ended_games_count)
    {
        $stats_data_layer_obj=new StatsGameDataLayer();
        $data = $stats_data_layer_obj->getAttemptsNoData($collection, $ended_games_count);
        return $data;
    }

    /**
     * Return comparing between user and other players
     *
     * @param  var
     * @return  array
     */
    public function getRank($collection, $user_id)
    {
        $stats_data_layer_obj=new StatsGameDataLayer();
        $data = $stats_data_layer_obj->getRank($collection,$user_id);
        return $data;
    }
}
