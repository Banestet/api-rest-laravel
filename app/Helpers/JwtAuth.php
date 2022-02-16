<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use illuminate\support\Facades\DB;

use App\Models\User as ModelsUser;

class  JwtAuth
{

    public $key;

    public function __construct()
    {
        $this->key = 'esto_es_una_clave_super_secreta-19993005';
    }

    public function signup($email, $password, $getToken = null)
    {
        //buscar si exite el ususaio con sus credenciales
        $user = ModelsUser::where([
            'email' => $email,
            'password' => $password
        ])->first();

        //comprobar si son correctas(objeto)
        $singup = false;
        if (is_object($user)) {
            $singup = true;
        }
        //generar el token del usuario identificado
        if ($singup) {
            $token = array(
                'sub'   =>  $user->id, //hace referencia al id del usuario
                'email' =>  $user->email,
                'name'  =>  $user->name,
                'surname' => $user->surname,
                'iat'   =>  time(),
                'exp'   => time() + (7 * 24 * 60 * 60) //para cauducar el token en una semana
            );

            //utlizamos la libreria JWT apra generar el token
            $jwt = JWT::encode($token, $this->key, 'HS256');
            //devolver los datos decofificados o el token, en funcion de un parametro 
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);   //variable decodificada
            if (is_null($getToken)) { //si es null entonces me devuelve el token
                $data = $jwt;
            } else {
                $data = $decoded;
            }
        } else {
            $data = array(
                'status' => 'error',
                'message' => 'Login incorrecto.'
            );
        }



        return $data;
    }


    public function checkToken($jwt, $getIdentify = false)
    {
        $auth = false;
        try {
            $jwt = str_replace('"','',$jwt); //remplazamos las comillas dobles del token para leerlo correctamentre y hacer e check
            $decoded = jwt::decode($jwt, $this->key, ['HS256']);
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if (!empty($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }
        if ($getIdentify) {
            return $decoded;
        }


        return $auth;
    }
}
