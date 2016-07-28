<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArcadeTB extends Model
{
    protected $table = 'dictionary.user_arcade_fact';
    protected $primaryKey = 'user_arcade_id';
    public $timestamps = false;

    public function user(){
    	return $this->belongsTo('App\User','user_id','user_id');
    }

    public function arcade(){
    	return $this->belongsTo('App\ArcadesTB','arcade_id','user_arcade_id');
    }
}
