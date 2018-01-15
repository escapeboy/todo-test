<?php

namespace App\Models;

use App\Traits\Helper;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use Helper;

    public $timestamps = true;
    protected $table = 'files';
    protected $fillable = ['filepath', 'extension', 'mime_type', 'size', 'original_name', 'title'];
    protected $visible = ['filepath', 'extension', 'mime_type', 'size', 'original_name', 'title'];

    protected $casts = [
        'size' => 'double',
    ];

}