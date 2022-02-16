<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category as ModelsCategory;
use App\Models\Post as ModelsPost;

class PruebasController extends Controller
{
    //metodo para pasar datos a una vista como este arreglo pasarlo a la vista index
    public function animal(){
        $titulo ='amimales';
        $animales =['perro','gato','tigres'];
        return view( 'pruebas.index', array(
            'titulo' => $titulo,
            'animales'=>$animales
        ));
    }


    //metodo para mandar a llamar todos los datos de la tabla posts 
/*
    public function testOrm(){
        //es como un select * from 
        $posts = ModelsPost :: all();
        //for para mandar a llamar todos los datos de la tabla posts con los datos que se quieran llamar
        foreach ($posts as $ModelsPost){
            echo "<h1>".$ModelsPost->title."</h1>";
            echo "<p>".$ModelsPost->content."</p>";
            
            //para impirmir datos relacionados
            echo "<spam>{$ModelsPost->user->name} - {$ModelsPost->category->name}</spam>";//para sacar datos relacionados 
            echo "<hr>";
        }
        die();
    }
*/

    public function testOrm(){
        $categories = ModelsCategory :: all();
        foreach ($categories as $ModelsCategory){
            echo "<h1>categorias: {$ModelsCategory->name}</h1>";
            echo "<hr>";
        foreach ($ModelsCategory -> posts as  $ModelsPost){
            echo "<h3> titulo: ".$ModelsPost->title."</h3>";
            echo "<spam>relacion usuario :{$ModelsPost->user->name} -a- {$ModelsPost->category->name}</spam>";//para sacar datos relacionados 
            echo "<p>contenido: ".$ModelsPost->content."</p>";
         
        }
        echo '<hr>';
    }
      
    }


    //metodo para mandar a llamar una vista
        public function index(){
            return view('pruebas');
        }

}
