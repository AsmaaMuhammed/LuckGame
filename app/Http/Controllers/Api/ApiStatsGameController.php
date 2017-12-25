<?php

namespace App\Http\Controllers\Api;

use App\Http\ServiceLayer\StatsGameServiceLayer;
use App\Http\ServiceLayer\LoginServiceLayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiStatsGameController extends Controller
{
    /**
     * Return data of game Statistics
     *
     * @param  user_id & token
     * @return  boolean or json
     */
    public static function gameStatistics($user_id, $token)
    {
        $login_service_layer_obj = new LoginServiceLayer();
        $states_service_layer_obj = new StatsGameServiceLayer();
        $user=$login_service_layer_obj->checkAuth($user_id, $token);

        if($user !== false)
        {
            $get_counts = $states_service_layer_obj->getGamesCount($user_id);
            $data['totals'] = $get_counts;

            //user attempts
            $collection=$states_service_layer_obj->getUserAttempts($user_id);

            $data['games_time']= $states_service_layer_obj->getAttemptsTimeData($collection, $get_counts['ended']);

            $data['games_attempts']=$states_service_layer_obj->getAttemptsNoData($collection, $get_counts['ended']);

            $data['rank']=$states_service_layer_obj->getRank($collection, $user_id);
            return json_encode($data);

        }
        else
            return json_encode(false);
    }
}
