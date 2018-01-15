<?php

namespace App;

use App\Models\Lists;
use App\Models\Tasks;
use App\Scopes\OrderByDefaultScope;
use App\Traits\Helper;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles, Helper;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function lists()
    {
        return $this->morphToMany(Lists::class, 'relation', 'lists_relations')->wherePivot('connection', 'user');
    }

    public function tasks()
    {
        return $this->morphToMany(Tasks::class, 'relation', 'tasks_relations')->wherePivot('connection', 'user');
    }

    public function getRouteAttribute()
    {
        return route('users.form', ['item' => $this->id]);
    }

    protected function bootIfNotBooted()
    {
        parent::boot();
        static::addGlobalScope(new OrderByDefaultScope('created_at', 'desc'));
    }
}
