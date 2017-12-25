<?php

namespace App\Http\DataLayer;

use App\Model\GameAttempts;
use App\Model\Games;
use Illuminate\Http\Request;


class StartGameDataLayer
{
    /**
     * Return game after create it
     *
     * @param  var
     * @return  object
     */
    public function createUserGame($user_id, $game_no, $a, $b)
    {
        $game=Games::create([
            'user_id'=>$user_id,
            'game_no'=>$game_no,
            'a'=>$a,
            'b'=>$b,
            'ended'=>0
        ]);
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
        $game = Games::find($game_id);
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
        $game = Games::find($game_id);
        $game->ended=1;
        $game->save();
        return true;
    }

    /**
     * Return boolean after insert the attempts
     *
     * @param  var
     * @return  boolean
     */
    public function insertAttempts($game_id, $attempts)
    {
        foreach ($attempts as $key => $attempt) {
            GameAttempts::create([
                'user_game_id' => $game_id,
                'attempt_no' => $attempt['attempt_no'],
                'attempt_time' => $attempt['attempt_time'],
            ]);
        }
        return true;
    }

}
