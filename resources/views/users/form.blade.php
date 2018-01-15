@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Users
                    </div>

                    <div class="panel-body">

                        @if ($message = session('message'))
                            <div class="alert alert-{{$message['type']}}">
                                {!! $message['text'] !!}
                            </div>
                        @endif

                        <form action="" method="post" role="form">

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Име"
                                       value="{{old('name', $item->name)}}" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email"
                                       value="{{old('email', $item->email)}}" required>
                            </div>

                            <div class="form-group">
                                <label>Roles</label>
                                <select class="form-control select2" name="roles" id="roles" multiple
                                        data-placeholder="Set user roles">
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{$role->name}}"
                                                @if($item->hasRole($role->name)) selected @endif >{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Change password</label>
                                <input type="password" class="form-control" name="new_password" id="new_password"
                                       placeholder="New password" value="">
                            </div>
                            <div class="form-group">
                                <label>Confirm password</label>
                                <input type="password" class="form-control" name="confirm_password"
                                       id="confirm_password" placeholder="Confirm password" value="">
                            </div>

                            {{csrf_field()}}
                            <button type="submit" class="btn btn-primary">Запиши</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop