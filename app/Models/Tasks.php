<?php

namespace App\Models;

use App\Scopes\OrderByDefaultScope;
use App\Traits\Helper;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tasks extends Model
{
    use Helper;
    public $timestamps = true;
    protected $table = 'tasks';

    use SoftDeletes;
    protected $dates = ['deleted_at', 'finished_on', 'due_date'];
    protected $fillable = ['title', 'description', 'due_date', 'owner_id', 'priority', 'finished_on'];

    protected $casts = ['priority' => 'integer'];

    public function tags()
    {
        return $this->morphedByMany(Tags::class, 'relation', 'tasks_relations')->wherePivot('connection', 'tag');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'relation', 'tasks_relations')->wherePivot('connection', 'user');
    }

    public function getRouteAttribute()
    {
        return route('tasks.form', ['item' => $this->id]);
    }

    public function lists()
    {
        return $this->morphToMany(Lists::class, 'relation', 'lists_relations')->wherePivot('connection', 'task');
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new OrderByDefaultScope('created_at', 'desc'));
    }
}