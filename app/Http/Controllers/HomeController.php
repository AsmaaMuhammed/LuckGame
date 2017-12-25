<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\ApiStartGameController;
use App\Http\Controllers\Api\ApiStatsGameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=$user = Auth::user();
        $user_states = ApiStatsGameController::gameStatistics($user->id,$user->token);
        $user_states = json_decode($user_states,true);
        return view('home',compact('user_states','user'));
    }

}
