<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TranslateTB extends Model
{
     protected $table = 'dictionary.translation_fact';
     protected $primaryKey = 'translation_id';
     public $timestamps = false;
}
