@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Отзывы</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> PM helper</a></li>
    <li class="active"> Отзывы</li>
</ol>
@stop

@section('content')
<div class="row">
    @if(Session::has('message'))
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              {{Session::get('message')}}
            </div>
        </div>
    @endif
    @can('user-unconfirmed');
        <div class="col-md-12">
            <div class="box box-comments">
                <div class="box-header">
                    <form method="GET" action="{{ url('/comments/add') }}" id="addCommentForm" style="display:none;">
                        <input type="hidden" name="fromPage" value="{{ Route::current()->getName() }}" id="fromPage">
                    </form>
                    
                    <button class="btn btn-primary btn-sm pull-left" type="submit" onclick = "document.getElementById('addCommentForm').submit();"><i class="fa fa-user-plus pull-left"></i>Добавить отзыв</button>
                </div>
            </div>
        </div>
    @endcan
    @foreach ($data['comments'] as $comment)
        <div class="col-xs-4">
            <div class="box box-success">
                <div class="box-header box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ isset($comment->owner) ? isset($comment->owner->logo) ? Storage::url($comment->owner->logo) : '/img/noname.png' : '/img/noname.png' }}" alt="User profile picture">
                    <h3 class="profile-username text-center">{{ $comment->author_name }}</h3>
                    <p class="text-center">
                        {!! isset($comment->owner) ? $comment->owner->name.'<br>' : '' !!}
                        <span class="text-muted">{{$comment->author_position }}</span>
                        @if (isset($comment->aboutUser))
                            <hr>
                            О компании: {{ $comment->aboutUser->name }}
                        @endif
                    </p>
                </div> <!-- /.box-header -->

                <div class="box-body">
                    <hr>
                    <p>{{ $comment->description }}</p>
                    <p class="text-right">
                        <small class="text-muted">
                            {{ isset($comment->created_at) ? \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i') : '' }}
                        </small>
                    </p>
                </div> <!-- /.box-body -->
            </div> <!-- /.box -->
        </div>
        @if($loop->iteration % 3 == 0)
            <div class="row"></div>
        @endif
    @endforeach


    
</div>
<!-- /.row -->
@stop
