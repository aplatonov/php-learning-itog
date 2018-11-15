@extends('adminlte::page')

@section('title', config('app.name', 'PM helper'))

@section('content_header')
    <h1>Новый отзыв</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
        <li><a href="#"> Отзывы</a></li>
        <li class="active"> Новый отзыв</li>
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
                    <input type="hidden" name="isUpdate" value="0">
                    <input type="hidden" name="company_id" value="{{ isset($form['company_id']) ? $form['company_id'] : 'null' }}">
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <div class="box-body">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-body">
                                        <div class="form-group col-xs-12">
                                            <label for="author_name">Автор</label>
                                            <input type="text" class="form-control" id="author_name" name="author_name" value="{{ Auth::user()->contact_person }}" required>
                                            @if ($errors->has('author_name'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('author_name') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label for="author_position">Должность автора</label>
                                            <input type="text" class="form-control" id="author_position" name="author_position" value="{{ old('author_position') }}">
                                            @if ($errors->has('author_position'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('author_position') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label for="description">Содержание отзыва</label>
                                            <textarea name="description" id="description" class="form-control" placeholder="Введите содержание отзыва" rows="3">{{ old('description') }}</textarea>

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
                                    <div class="box-header">
                                        <h3 class="box-title">Отзыв будет оправлен от</h3>
                                    </div>
                                    <div class="box-body">
                                        <p>{{ Auth::user()->name }}</p>
                                        <p>{!! isset($form['company_name']) ? 'в адрес менеджера <strong>'.$form['company_name'].'</strong>' : '' !!}</p>
                                    </div>
                                </div>
                            </div>
                            <!-- right column END -->
                        </div>
                    </div><!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <a href="{{ Redirect::back()->getTargetUrl() }}"><button type="button" class="btn btn-default">Отмена</button></a>
                        <button type="submit" class="btn btn-success pull-right" onclick="document.getElementById('preloader').style.display = 'block'">Сохранить отзыв</button>
                    </div>
                </form>
                <div class="overlay" id="preloader" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
@stop
