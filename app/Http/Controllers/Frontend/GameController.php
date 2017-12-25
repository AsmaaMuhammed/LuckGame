<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Api\ApiStartGameController;
use App\Http\ServiceLayer\LoginServiceLayer;
use App\Http\ServiceLayer\StartGameServiceLayer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the uer statictics
     *
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function startGame(Request $request)
    {
        $user_id=$_POST['user_id'];
        $token=$_POST['token'];
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
            $data=[
                'game_id'=>$game->id,
                'a'=>$a,
                'b'=>$b
            ];
            return $data;
        }
        else
            return json_encode(false);
    }
    public function endGame()
    {
        $attempts=$_POST['attempts'];
        $token=$_POST['token'];
        $game_id=$_POST['game_id'];
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
