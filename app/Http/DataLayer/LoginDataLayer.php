<?php

namespace App\Http\DataLayer;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginDataLayer
{
    /**
     * Return boolean after check user email if exists
     *
     * @param  var
     * @return  boolean
     */
    public function checkEmail($email)
    {
        $check_email = User::where('email', $email)->first();
        if (isset($check_email))//exists
            return true;
        else
            return false;
    }
    /**
     * Return data of registered user
     *
     * @param  var
     * @return  object
     */
    public function register($email, $password, $token)
    {
        $user = User::create([
            'email' => $email,
            'password' => Hash::make($password),
            'token' => $token
        ]);
        return $user;
    }
    /**
     * Return boolean after validate of logged user
     *
     * @param  var
     * @return  boolean
     */
    public function validateUser($email, $password)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password]))
            return true;
        else
            return false;
    }
    /**
     * Return user after change the token he will use it when call the services
     *
     * @param  var
     * @return  object
     */
    public function login($email, $token)
    {
        $user=User::where('email',$email)->first();
        $user->fill(['token'=>$token])->save();
        return $user;
    }
    /**
     * Return user after check auth
     *
     * @param  var
     * @return  boolean or object
     */
    public function checkAuth($user_id,$token)
    {
        $user=User::find($user_id);
        if ($user->token == $token)//Authenticated
            return $user;
        else
            return false;
    }

}
