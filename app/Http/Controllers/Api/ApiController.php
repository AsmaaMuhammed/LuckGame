<?php

namespace App\Http\Controllers\Api;

use App\Model\GameAttempts;
use App\Model\Games;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function register(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $check_email = User::where('email', $email)->first();
        //check if the email is exist before
        if (!isset($check_email))
        {
            //generate token
            $token=substr(str_shuffle("0123456789abcdefgehijklmnopqrstuvwxyzABCDEFGEHIJKLMNOPQRSTUVWXWZ"), 0,60);
            $user = User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'token' => $token
            ]);
            return json_encode(['id' => $user->id, 'email' => $user->email, 'token' => $user->token]);

        }
        else
            return json_encode(false);
    }
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password]))
        {
            //generate token
            $token=substr(str_shuffle("0123456789abcdefgehijklmnopqrstuvwxyzABCDEFGEHIJKLMNOPQRSTUVWXWZ"), 0,60);

            $user=User::where('email',$email)->first();
            $user->fill(['token'=>$token])->save();

            return json_encode([
                'id'=>$user->id,
                'email'=>$user->email,
                'token'=>$token
            ]);
        }
        else
            return json_encode(false);
    }

    public static function startGame(Request $request)
    {
        $user_id = $request->input('user_id');
        $token = $request->input('token');
        $user=User::find($user_id);
        if($user->token==$token) {
            $user_games=$user->games;
            $max_no_game=collect($user_games)->max('game_no');
            //dd($max_no_game);
            //calc game no from last no of the user game
            $game_no=!isset($max_no_game)? 1: $max_no_game + 1;
            //dd($game_no);
            $a=rand(20,30);
            $b=rand(0,$a);
            $game=Games::create([
                'user_id'=>$user_id,
                'game_no'=>$game_no,
                'a'=>$a,
                'b'=>$b,
                'ended'=>0
            ]);
            return json_encode([
                'game_id'=>$game->id,
                'a'=>$a,
                'b'=>$b
            ]);
        }
        else
            return json_encode(false);
    }
    public function endGame(Request $request, $game_id)
    {
        $attempts =$request->input('attempts');
        $token = $request->input('token');

        $attempts =json_decode($attempts, true);
        $game = Games::find($game_id);
        $user = $game->user;
        //dd($user);
        if ($user->token == $token) {

            //mark the game as ended
            $game->ended=1;
            $game->save();

            //insert the attempts of this game
            /******** just test data *****/
            $attempts=json_encode([
                ['attempt_no'=>2,'attempt_time'=>3],
                ['attempt_no'=>3,'attempt_time'=>25]
            ]);
            //dd(json_decode($attempts,true));
            $attempts =json_decode($attempts,true);
            //end
            foreach ($attempts as $key => $attempt) {
                GameAttempts::create([
                    'user_game_id' => $game_id,
                    'attempt_no' => $attempt['attempt_no'],
                    'attempt_time' => $attempt['attempt_time'],
                ]);
            }
            return json_encode(true);
        }
        else
            return json_encode(false);
    }
    public static function gameStatistics($user_id, $token)
    {
        $user=User::find($user_id);
        if ($user->token == $token) {

            $user_games = $user->games;
            $ended_games = $user->endedgames;

            //a
            $all_games_count = $user_games->count();
            $ended_games_count = $ended_games->count();
            $ended_ratio = $ended_games_count == 0 ? 0 : round($all_games_count/$ended_games_count, 3);
            $data['totals']=['total'=>$all_games_count,'ended_ratio'=>$ended_ratio];

            //b
            $collection=User::join('user_games','user_id','=','users.id')
                ->join('game_attempts','user_games.id','=','user_game_id')
                ->select( DB::raw('count(*) as attempt_count'), DB::raw('sum(attempt_time) as sum_attempts_times'), DB::raw('count(*) * sum(attempt_time) as game_score'))
                ->where('users.id',$user_id)
                ->groupBy('user_game_id')
                ->get();
            //dd($collection);
            $best_time = collect($collection)->min('sum_attempts_times');
            !isset($best_time) ? $best_time = 0 :'';
            $worst_time = collect($collection)->max('sum_attempts_times');
            !isset($worst_time) ? $worst_time = 0 :'';
            $average_time = $ended_games_count == 0 ? 0 : round(($best_time + $worst_time)/$ended_games_count, 3);
            $data['games_time']=['average'=>$average_time,'best'=>$best_time,'worst'=>$worst_time];

            //c
            $best_no = collect($collection)->min('attempt_count');
            !isset($best_no) ? $best_no = 0 :'';
            $worst_no = collect($collection)->max('attempt_count');
            !isset($worst_no) ? $worst_no = 0 :'';
            $average_no = $ended_games_count == 0 ? 0 : round(($best_no + $worst_no)/$ended_games_count);
            $data['games_attempts']=['average'=>$average_no,'best'=>$best_no,'worst'=>$worst_no];

            //d
            $rank=collect($collection)->min('game_score');
            $data['rank']=['me'=>$rank,'total'=>'I don not know what can i send'];

            return json_encode($data);
        }
        else
            return json_encode(false);
    }
}
