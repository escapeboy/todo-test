@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Tasks
                        @if($item && $item->lists->count())
                            <a href="{{route('lists.tasks', ['item' => $item->lists->first()->id])}}"
                               class="btn btn-link btn-xs pull-right">Go back to list</a>
                        @endif
                    </div>

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
                                <label>Due date</label>
                                <input class="form-control" type="date" placeholder="Due date" name="due_date"
                                       value="{{old('due_date', $item->due_date ? $item->due_date->format('Y-m-d') : null)}}">
                            </div>
                            <div class="form-group">
                                <select name="priority" id="priority" class="form-control">
                                    @for($i=1;$i<10;$i++)
                                        <option value="{{$i}}" @if($item->priority==$i) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
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
                            <div class="form-group">
                                <label>Finished on</label>
                                <input class="form-control" type="date" placeholder="Finished on" name="finished_on"
                                       value="{{old('finished_on', $item->finished_on ? $item->finished_on->format('Y-m-d') : null)}}">
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