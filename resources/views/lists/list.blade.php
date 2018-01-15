@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Lists
                        <a href="{{route('lists.export', ['format' => 'xls'])}}" class="btn btn-link btn-xs pull-right">Export
                            list as xls</a>
                    </div>

                    <div class="panel-body">
                        @if ($message = session('message'))
                            <div class="alert alert-{{$message['type']}}">
                                {!! $message['text'] !!}
                            </div>
                        @endif

                        <table class="table table-stripped">
                            <thead>
                            <tr>
                                <th colspan="7">
                                    <form action="" method="post" role="form">
                                        <div class="input-group col-xs-12">
                                            <input type="text" class="form-control" name="title" id="new_list_title"
                                                   autofocus
                                                   placeholder="Create new list">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i></button>
                                            </span>
                                        </div>
                                        {{csrf_field()}}
                                    </form>
                                </th>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Tasks</th>
                                <th>Users</th>
                                <th style="white-space: nowrap">Created at</th>
                                <th>Created by</th>
                                <th><i class="fa fa-cogs"></i></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($items))
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->title}}</td>
                                        <td class="text-center"><span
                                                    title="Unfinished">{{$item->tasks()->whereNull('finished_on')->count()}}</span>
                                            / <span title="All tasks">{{$item->tasks->count()}}</span></td>
                                        <td class="text-center">{{$item->users->count()}}</td>
                                        <td>{{$item->created_at->format('d.m.Y')}}</td>
                                        <td>{{$item->owner ? $item->owner->name : '-'}}</td>
                                        <td style="white-space: nowrap">
                                            <div class="btn-group">
                                                <a href="{{$item->route}}" class="btn btn-primary btn-xs"><i
                                                            class="fa fa-pencil"></i></a>
                                                <a href="{{route('lists.tasks', ['item' => $item->id])}}"
                                                   class="btn btn-success btn-xs"><i class="fa fa-tasks"></i></a>
                                                <a href="{{route('lists.delete', ['item' => $item->id])}}"
                                                   class="btn btn-danger btn-xs confirm"><i class="fa fa-trash"></i></a>
                                            </div>

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