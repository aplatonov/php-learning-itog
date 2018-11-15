@extends('adminlte::page')

@section('title', config('app.name', 'PM helper'))

@section('content_header')
    <h1>Редактирование отзыва</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
        <li><a href="#"> Администратор</a></li>
        <li class="active"> Редактирование отзыва</li>
    </ol>
@stop

@section('content')
    <div class="row>">
        @if(Session::has('message'))
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissable">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              {{Session::get('message')}}
            </div>
        </div>
        @endif
         <div class="col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Данные отзыва</h3>
                </div><!-- /.box-header -->
                <form role="form" method="POST" action="{{action('CommentsController@storeComment')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="isUpdate" value="1">
                    <input type="hidden" name="comment_id" value="{{ $comment->id }}">
                    <div class="box-body">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="form-group col-xs-12">
                                            <label>Отзыв от имени</label>
                                            <select id="user_id" name="user_id" class="form-control" name="user_id">
                                                @foreach($companies as $company)
                                                    @if ($company->id == $comment->user_id)
                                                        <option selected value="{{$company->id}}">{{$company->name}}</option>
                                                    @else
                                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('user_id'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('user_id') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label for="author_name">Автор</label>
                                            <input type="text" class="form-control" id="author_name" name="author_name" value="{{ $comment->author_name }}" required>
                                            @if ($errors->has('author_name'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('author_name') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label for="author_position">Должность автора</label>
                                            <input type="text" class="form-control" id="author_position" name="author_position" value="{{ $comment->author_position }}">
                                            @if ($errors->has('author_position'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('author_position') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label for="description">Содержание отзыва</label>
                                            <textarea name="description" id="description" class="form-control" placeholder="Введите содержание отзыва" rows="3">{{ $comment->description }}</textarea>

                                            @if ($errors->has('description'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('description') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>

                            </div>
                            <!-- right column -->
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="form-group col-xs-12">
                                            <label>Отзыв о компании</label>
                                            <select id="company_id" name="company_id" class="form-control" name="company_id">
                                                <option value="0">Укажите компанию о которой дается отзыв</option>
                                                @foreach($companies as $company)
                                                    @if ($company->id == $comment->company_id)
                                                        <option selected value="{{$company->id}}">{{$company->name}}</option>
                                                    @else
                                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('company_id'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('company_id') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <div class="checkbox">
                                                <br>
                                                <label for="active">
                                                    <input type="checkbox" id="active" name="active" {{ $comment->active ? 'checked' : ''}} value="1">&nbsp;Активен</label>
                                            </div>
                                            @if ($errors->has('active'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('active') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-8">
                                            <div class="checkbox">
                                                <br>
                                                <label for="visible_on_main">
                                                    <input type="checkbox" id="visible_on_main" name="visible_on_main" {{ $comment->visible_on_main ? 'checked' : ''}} value="1">&nbsp;Показывать в "Отзывах"</label>
                                            </div>
                                            @if ($errors->has('visible_on_main'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('visible_on_main') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- right column END -->
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <a href="{{ Redirect::back()->getTargetUrl() }}"><button type="button" class="btn btn-default">Отмена</button></a>
                        <button type="submit" class="btn btn-success pull-right">Сохранить отзыв</button>
                    </div>
                </form>
            </div><!-- /.box -->
        </div>
    </div>
@stop
