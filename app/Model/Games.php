<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'game_no', 'user_id', 'a', 'b', 'ended'
    ];
    protected $table = 'user_games';

    /**
     * define the relationship between game & attempts
     *
     * @var array
     */
    public function attempts()
    {
        return $this->hasMany('App\Model\GameAttempts','user_game_id');
    }
    /**
     * define the relationship between game & user
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
