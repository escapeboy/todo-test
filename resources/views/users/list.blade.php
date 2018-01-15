@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Users</div>

                    <div class="panel-body">
                        @if ($message = session('message'))
                            <div class="alert alert-{{$message['type']}}">
                                {!! $message['text'] !!}
                            </div>
                        @endif

                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Tasks</th>
                                <th>Lists</th>
                                <th><i class="fa fa-cogs"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($items))
                                @foreach($items as $item)
                                    <tr @if($item->finished_on) style="text-decoration: line-through; opacity: 0.5" @endif >
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->email}}</td>
                                        <td class="text-center">{{$item->tasks->count()}} <a
                                                    href="{{route('tasks', ['owner_id' => $item->id])}}"><i
                                                        class="fa fa-eye"></i></a></td>
                                        <td class="text-center">{{$item->lists->count()}} <a
                                                    href="{{route('lists', ['owner_id' => $item->id])}}"><i
                                                        class="fa fa-eye"></i></a></td>

                                        <td style="white-space: nowrap">
                                            @if(!$item->hasRole('admin') || ($item->hasRole('admin') && $item->id == auth()->user()->id))
                                                <div class="btn-group">
                                                    <a href="{{$item->route}}" class="btn btn-primary btn-xs"><i
                                                                class="fa fa-pencil"></i></a>
                                                    <a href="{{route('users.delete', ['item' => $item->id])}}"
                                                       class="btn btn-danger btn-xs confirm"><i class="fa fa-trash"></i></a>
                                                </div>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="7" class="text-center">
                                        {{$items->links()}}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="7">
                                        <div class="alert alert-info">
                                            <p>No items found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop