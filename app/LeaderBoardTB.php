<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaderBoardTB extends Model
{
    protected $table = 'dictionary.user_leaderboard_tb';
    protected $primaryKey = 'user_leaderboard_id';
    public $timestamps = false;


    public function user_arcade()
    {
        return $this->hasOne('App\UserArcadeTB','user_id','user_id');
    }
}
