<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrderByDefaultScope implements Scope
{

    private $direction, $field;

    public function __construct($field = 'created_at', $direction = 'desc')
    {
        $this->direction = $direction;
        $this->field = $field;
    }

    public function apply(Builder $builder, Model $model)
    {
        return $builder->orderBy($this->field, $this->direction);
    }
}