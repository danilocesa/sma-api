<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $table = 'dictionary.user_setting_fact';
    protected $primaryKey = 'user_setting_id';
    public $timestamps = false;


    public function user(){
    	return $this->belongsTo('App\User','user_id','user_id');
    }


}
