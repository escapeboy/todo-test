<?php

namespace App\Models;


use App\Events\ListCreate;
use App\Scopes\OrderByDefaultScope;
use App\Traits\Helper;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lists extends Model
{
    use Helper;

    public $timestamps = true;
    protected $table = 'lists';

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = ['title', 'description', 'owner_id'];


    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->morphedByMany(Tasks::class, 'relation', 'lists_relations')->wherePivot('connection', 'task');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'relation', 'lists_relations')->wherePivot('connection', 'user');
    }

    public function tags()
    {
        return $this->morphedByMany(Tags::class, 'relation', 'lists_relations')->wherePivot('connection', 'tag');
    }

    public function getRouteAttribute()
    {
        return route('lists.form', ['item' => $this->id]);
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new OrderByDefaultScope('created_at', 'desc'));
    }
}