<?php

namespace App\Http\ServiceLayer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\DataLayer\LoginDataLayer;

class LoginServiceLayer
{
    /**
     * Return boolean after check user email if exists
     *
     * @param  var
     * @return  boolean
     */
    public function checkEmail($email)
    {
        $data_layer_obj=new LoginDataLayer();
        $res = $data_layer_obj->checkEmail($email);
        return $res;
    }
    /**
     * Return data of registered user
     *
     * @param  var
     * @return  object
     */
    public function register($email, $password)
    {
        $data_layer_obj=new LoginDataLayer();
        //generate token
        $token=substr(str_shuffle("0123456789abcdefgehijklmnopqrstuvwxyzABCDEFGEHIJKLMNOPQRSTUVWXWZ"), 0,60);
        $user=$data_layer_obj->register($email, $password, $token);
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
        $data_layer_obj=new LoginDataLayer();
        $res = $data_layer_obj->validateUser($email, $password);
        return $res;
    }

    /**
     * Return user after change the token he will use it when call the services
     *
     * @param  var
     * @return  object
     */
    public function login($email)
    {
        $data_layer_obj=new LoginDataLayer();
        //generate token
        $token=substr(str_shuffle("0123456789abcdefgehijklmnopqrstuvwxyzABCDEFGEHIJKLMNOPQRSTUVWXWZ"), 0,60);
        $uer=$data_layer_obj->login($email, $token);
        return $uer;
    }

    /**
     * Return user after check auth
     *
     * @param  var
     * @return  boolean or object
     */
    public function checkAuth($user_id,$token)
    {
        $data_layer_obj=new LoginDataLayer();
        $res = $data_layer_obj->checkAuth($user_id,$token);
        return $res;
    }

}
