<?php

namespace App\Http\ServiceLayer;

use App\Http\DataLayer\StartGameDataLayer;
use Illuminate\Http\Request;

class StartGameServiceLayer
{
    /**
     * Return game after create it
     *
     * @param  var
     * @return  object
     */
    public function createUserGame($user_id, $game_no, $a, $b)
    {
        $startgame_data_layer_obj = new StartGameDataLayer();
        $game = $startgame_data_layer_obj->createUserGame($user_id, $game_no, $a, $b);
        return $game;
    }

    /**
     * Return game
     *
     * @param  var
     * @return  object
     */
    public function getGame($game_id)
    {
        $startgame_data_layer_obj = new StartGameDataLayer();
        $game = $startgame_data_layer_obj->getGame($game_id);
        return $game;
    }

    /**
     * Return boolean after change status of game to ended
     *
     * @param  var
     * @return  boolean
     */
    public function updateGame($game_id)
    {
        $startgame_data_layer_obj = new StartGameDataLayer();
        $res = $startgame_data_layer_obj->updateGame($game_id);
        return $res;
    }
    /**
     * Return boolean after insert the attempts
     *
     * @param  var
     * @return  boolean
     */
    public function insertAttempts($game_id, $attempts)
    {
        $startgame_data_layer_obj = new StartGameDataLayer();
        $res = $startgame_data_layer_obj->insertAttempts($game_id, $attempts);
        return $res;
    }
}
