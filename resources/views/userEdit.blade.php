@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Редактирование профиля менеджера</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> Личный кабинет</a></li>
    <li class="active"> Редактирование профиля</li>
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
    <div class="col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Редактирование профиля <strong>{{ $user->login }}</strong></h3>
            </div> <!-- /.box-header -->
                <!-- form start -->
            <form role="form" method="POST" enctype="multipart/form-data" action="{{ action('UserController@update', ['user'=>$user->id]) }}">
            <div class="box-body">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Основная информация</h3>
                            </div>
                            <div class="box-body">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                            
                                <div class="col-xs-12 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name">Имя (наименование)</label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xs-12 form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description">Информация о менеджере</label>
                                    <textarea name="description" id="description" class="form-control" placeholder="Введите информацию о менеджере" rows="3">{{ $user->description }}</textarea>

                                    @if ($errors->has('description'))
                                        <span class="text-danger">
                                            <strong><small>{{ $errors->first('description') }}</small></strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xs-12 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email">E-Mail</label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xs-12 form-group{{ $errors->has('contact_person') ? ' has-error' : '' }}">
                                    <label for="contact_person">Полное имя</label>
                                    <input id="contact_person" type="text" class="form-control" name="contact_person" value="{{ $user->contact_person }}" required>
                                    @if ($errors->has('contact_person'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact_person') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xs-6 form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                    <label for="phone">Телефон</label>
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required>
                                    @if ($errors->has('phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-xs-6 form-group{{ $errors->has('www') ? ' has-error' : '' }}">
                                    <label for="www">www</label>
                                    <input id="www" type="text" class="form-control" name="www" value="{{ $user->www }}" placeholder="www.mysite.com">
                                    @if ($errors->has('www'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('www') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                @if (!Auth::guest() && Auth::user()->confirmed == 0)
                                    <div class="form-group">
                                        <label class="col-xs-12 control-label"><span class="badge badge-important">Логин не подтвержден!</span><br><small>Вы не можете добавлять объекты и просматривать контактные данные</small></label>
                                    </div>
                                @endif
                                @if (!Auth::guest() && Auth::user()->valid == 0)
                                    <div class="form-group">
                                        <label class="col-xs-12 control-label"><span class="badge badge-warning">Логин заблокирован!</span><br><small>обратитесь к администратору</small></label>
                                    </div>
                                @endif
                                <div class="form-group col-xs-6" @if(Auth::user()->isAdmin() == true) style="display: block;" @else style="display: none;" @endif>
                                    <div class="checkbox">
                                        <label for="valid">
                                            <input type="checkbox" id="valid" name="valid" {{ $user->valid ? 'checked' : ''}} value="1">
                                            Действующий
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-xs-6" @if(Auth::user()->isAdmin() == true) style="display: block;" @else style="display: none;" @endif>
                                    <div class="checkbox">
                                        <label for="confirmed">
                                            <input type="checkbox" id="confirmed" name="confirmed" {{ $user->confirmed ? 'checked' : ''}} value="1">
                                            Подтвержден
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- /left column -->
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header with-border">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Дополнительная информация</h3>
                                </div>
                                <div class="box-body">
                                    <div class="col-xs-12 form-group{{ $errors->has('portfolio') ? ' has-error' : '' }}">
                                        <label for="portfolio">Портфолио <small>(pdf, rtf, doc)</small></label>
                                        <div class="col-xs-12">
                                            <p class="form-control-static">
                                                @if ($user->portfolio)
                                                    <span class="label label-success">Есть!&nbsp;</span>
                                                    <a href="{{ isset($user->portfolio) ? Storage::url($user->portfolio) : '' }}">Посмотреть</a><br><label class="control-label">Заменить портфолио:</label>
                                                @endif
                                                <input id="portfolio" type="file" name="portfolio" value="{{ old('portfolio') }}" accept=".pdf,.doc,.docx,.rtf">
                                            </p>
                                            @if ($errors->has('portfolio'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('portfolio') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xs-12 form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                                        <label for="logo">Логотип <small>(jpg, png ,jpeg, gif)</small></label>
                                        <div class="col-xs-12 text-center">

                                            <div class="center-block" style="width: 220px; height: 220px; margin-bottom: 5px;">
                                                <img src="{{ isset($user->logo) ? Storage::url($user->logo) : '/img/noname.png' }}" id="img" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                                <input type="file" name="logo" id="logo" accept=".jpg,.png,.jpeg,.gif" style="display: none; ">
                                            </div>
                                            <a href="##" id="browse_file" onclick="return false;">Добавить логотип</a>

                                            @if ($errors->has('logo'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('logo') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-body">

                            </div>
                        </div>
                    </div> <!-- /right column -->
                </div> <!-- /row for columns -->
            </div> <!-- /box body -->

            <div class="box-footer">
                <a href="{{ url('/home') }}"><button type="button" class="btn btn-default">Отмена</button></a>
                @if(Auth::user()->isAdmin() || Auth::user()->id == $user->id)
                    <button type="submit" class="btn btn-success pull-right" onclick="document.getElementById('preloader').style.display = 'block'">Сохранить</button>
                @endif
            </div>
            <!-- /.box-footer -->
            </form>
            <div class="overlay" id="preloader" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div> <!-- /.box -->
    </div> <!-- /.col -->
</div> <!-- /.row -->
@stop

@section('jscripts')
    <script>
        $('#browse_file').on('click', function(e){
            $('#logo').click();
        });
        $('#logo').on('change', function(e){
            var fileInput = this;
            if (fileInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e)
                {
                    $('#img').attr('src', e.target.result);
                }
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    </script>
    <script>
        jQuery(function($){
            $("#phone").mask("+7 (999) 999-99-99", {placeholder:"x"});
        });
    </script>
@endsection