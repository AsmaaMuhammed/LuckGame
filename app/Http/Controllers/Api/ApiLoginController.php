<?php

namespace App\Http\Controllers\Api;

use App\Http\ServiceLayer\LoginServiceLayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends Controller
{
    /**
     * Return data of registered user
     *
     * @param  \Illuminate\Http\Response
     * @return  boolean or json
     */
    public function register(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $service_layer_obj = new LoginServiceLayer();

        //check if the email is exist before
        $check_email=$service_layer_obj->checkEmail($email);
        if(!$check_email)// exists
        {
            $user=$service_layer_obj->register($email, $password);
            return json_encode(['id' => $user->id, 'email' => $user->email, 'token' => $user->token]);
        }
        else{
            return json_encode(false);
        }

    }
    /**
     * Return data of logged user
     *
     * @param  \Illuminate\Http\Response
     * @return  boolean or json
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $service_layer_obj = new LoginServiceLayer();
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = $service_layer_obj->login($email);
            return json_encode([
                'id' => $user->id,
                'email' => $user->email,
                'token' => $user->token
            ]);

        } else {
            return json_encode(false);
        }
    }
}
