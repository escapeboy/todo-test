@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Lists</div>

                    <div class="panel-body">
                        @if ($message = session('message'))
                            <div class="alert alert-{{$message['type']}}">
                                {!! $message['text'] !!}
                            </div>
                        @endif

                        <form action="" method="post" role="form">

                            <div class="form-group">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Заглавие"
                                       value="{{old('title', $item->title)}}">
                            </div>

                            <div class="form-group">
                                <textarea class="form-control" name="description" id="description"
                                          placeholder="Описание">{{old('description', $item->description)}}</textarea>
                            </div>
                            <div class="form-group">
                                <select class="form-control select2" name="users[]" id="users" multiple
                                        data-placeholder="Assigned users">
                                    @foreach(\App\User::all() as $user)
                                        <option value="{{$user->id}}"
                                                @if(in_array($user->id, (array)old('users', $item->users->pluck('id')->toArray()))) selected @endif>{{$user->name}}
                                            [{{$user->email}}]
                                        </option>
                                    @endforeach
                                </select>
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