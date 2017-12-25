<?php

namespace App\Http\Controllers\Api;

use App\Http\ServiceLayer\StartGameServiceLayer;
use App\Http\ServiceLayer\LoginServiceLayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiStartGameController extends Controller
{
    /**
     * Return data of created game
     *
     * @param  \Illuminate\Http\Response
     * @return  boolean or json
     */
    public static function startGame(Request $request)
    {
        $user_id = $request->input('user_id');
        $token = $request->input('token');

        $login_service_layer_obj = new LoginServiceLayer();
        $startgame_service_layer_obj = new StartGameServiceLayer();
        $user=$login_service_layer_obj->checkAuth($user_id, $token);

        if($user !== false)
        {
            $user_games=$user->games;
            $max_no_game=collect($user_games)->max('game_no');
            //calc game no from last no of the user game
            $game_no=!isset($max_no_game)? 1: $max_no_game + 1;
            $a=rand(20,30);
            $b=rand(0,$a);
            $game = $startgame_service_layer_obj->createUserGame($user_id, $game_no, $a, $b);
            return json_encode([
                'game_id'=>$game->id,
                'a'=>$a,
                'b'=>$b
            ]);
        }
        else
            return json_encode(false);
    }
    /**
     * Return true after save the attempts
     *
     * @param  \Illuminate\Http\Response
     * @return  boolean
     */
    public function endGame(Request $request, $game_id)
    {
        $attempts = $request->input('attempts');
        $token = $request->input('token');

        $attempts = json_decode($attempts, true);

        $login_service_layer_obj = new LoginServiceLayer();
        $startgame_service_layer_obj = new StartGameServiceLayer();

        $game = $startgame_service_layer_obj->getGame($game_id);
        $user = $game->user;
        $user = $login_service_layer_obj->checkAuth($user->id, $token);

        if ($user !== false) {
            //mark the game as ended
            $startgame_service_layer_obj->updateGame($game_id);

            //insert attempts
            $startgame_service_layer_obj->insertAttempts($game_id, $attempts);
            return json_encode(true);
        } else
            return json_encode(false);
    }
}
