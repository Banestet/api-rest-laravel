<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{



    public function pruebas(Request $request)
    {
        return "accion de pruebas de User-Controller";
    }

    //metodo para registrar datos
    /* Un api reste se basa e recivir unos datos preocesarlos y devolverlos en json  */


    public function register(Request $request)
    {
        //recoger los datos del usuario pór post
        $json = $request->input('json', null);


        //decodificar los datos (json)
        $params = json_decode($json); //obtener un obejto para php
        $params_array = json_decode($json, true); //obetenr un array



        //validar datos
        if (!empty($params) && !empty($params_array)) {
            //limpiamos los datos, el trim es para los espacios
            $params_array = array_map('trim', $params_array);
            //validar datos
            $validate =  Validator::make($params_array, [
                'name'      => 'required|alpha',
                'surname'   => 'required|alpha',
                'email'     => 'required|email|unique:users', //comprobarsi el usuario exisre ya (duplicado)
                'password'  => 'required'
            ]);
            //vamos a comprobar si tiene fallos con el metodo fails()
            if ($validate->fails()) {
                //validacion ha fallado
                $data = array(
                    'status'    => 'error',
                    'code'      => 404, //codigos de http
                    'message'   => 'el usuario no se ha creado',
                    'errors'    => $validate->errors()
                );
            } else {
                //la validacion pasa correctamente procedemos a 
                //cifrar la contraseña
                $pwd = hash('sha256', $params->password);
                //crear el usuario
                $user = new ModelsUser();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';
                //guardar el usuario en la base de datos
                $user->save();
                $data = array(
                    'status'    => 'success',
                    'code'      => 200, //codigos de http
                    'message'   => 'el usuario  se ha creado correctamente',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status'    => 'error',
                'code'      => 404, //codigos de http
                'message'   => 'los datos enviados no son correctos',
            );
        }





        return response()->json($data, $data['code']); //convertimos un array en datos json
        //porque un api rest tiene que devolver datos en json si no seria un servicio web
    }





    public function login(Request $request)
    {
        $jwtAuth = new \App\Helpers\JwtAuth;
        //recibir datos por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        //validar datos
        $validate =  Validator::make($params_array, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        //vamos a comprobar si tiene fallos con el metodo fails()
        if ($validate->fails()) {
            //validacion ha fallado
            $signup = array(
                'status'    => 'error',
                'code'      => 404, //codigos de http
                'message'   => 'el usuario no se ha podido identificar',
                'errors'    => $validate->errors()
            );
        } else {
            //cifrar la password
            $pwd = hash('sha256', $params->password);
            //devolver token o datos
            $signup =  $jwtAuth->signup($params->email, $pwd);

            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }
        return response()->json($signup, 200);
    }

public function update( Request $request){
    //comproar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth;
    $checkToken = $jwtAuth->checkToken($token);
    if ($checkToken){
        //actualizamos el usuario

        //recoger los datos por post
        $json= $request->input('json',null);
        $params_array = json_decode($json,true);
    
        //sacar usuario identificado
        $user = $jwtAuth->checkToken($token,true);

        //validar los datos
        $validate = validator:: make($params_array,[
            'name'      => 'required|alpha', //nombre formado por letras
            'surname'   => 'required|alpha',
            'email'     => 'required|email|unique:users,' .$user->sub//comprobarsi el usuario exisre ya (duplicado) con la excepcion de que puede actualizar el email orque el id es el mismo o sea el sub
        ]);
        //quitar los campos que no quiero actualizar
        unset($params_array['id']);
        unset($params_array['role']);
        unset($params_array['id']);
        unset($params_array['id']);
        //actualizar usuario en la bbdd
        //devolver array con resultado
        echo "<h1> Login Correcto</h1>";
    }else{
        $data= array(
            'code'=>400,
            'status'=>'error',
            'message'=>'El usuario no esta identificado'
        );
        echo "<h1>Login Incorrecto</h1>";
    }
    return response()->json($data,$data['code']);
}  

}
