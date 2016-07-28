<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArcadesTB extends Model
{
    protected $table = 'dictionary.arcades_tb';
    protected $primaryKey = 'arcade_id';
    public $timestamps = false;

    // public function userArcade(){
    // 	return $this->hasMany('App\UserArcadeTB','arcade_id');
    // }


}
