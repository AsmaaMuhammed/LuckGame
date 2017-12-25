<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class GameAttempts extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_game_id', 'attempt_no', 'attempt_time'
    ];

    protected $table = 'game_attempts';
}
