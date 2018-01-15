<?php

namespace App\Models;

use App\Traits\Helper;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use Helper;
    public $timestamps = true;
    protected $table = 'tags';
    protected $fillable = ['title'];

}