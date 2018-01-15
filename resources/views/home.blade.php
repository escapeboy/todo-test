@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @if ($message = session('message'))
                            <div class="alert alert-{{$message['type']}}">
                                {!! $message['text'] !!}
                            </div>
                        @endif

                        <dashboard></dashboard>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
