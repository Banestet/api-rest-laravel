<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{

//definir la tabla
    protected $table = 'posts';

    //relacion de uno a muchos pero inversa de (muchos a uno)
    //esto permite sacar los objetos de usuarios en base con el user_id
    //
    public function user(){
        return $this ->belongsTo('App\Models\User','user_id');
    }



    //sacame el objeto de categorio con base al category_id
    public function category(){
        return $this ->belongsTo('App\Models\Category','category_id');
    }

    use HasFactory;
}
