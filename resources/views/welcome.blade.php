@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
    <h1>Страница приветствия</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
        <li class="active"> Страница приветствия</li>
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

        <div class="col-md-6">
            <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Горячие предложения</h3>
                </div> <!-- /.box-header -->
                <div class="box-body">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                          <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                          <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                          <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                        </ol>
                        <div class="carousel-inner">
                            <div class="item active">
                                <img src="http://placehold.it/900x300/39CCCC/ffffff&text=PM helper!" alt="First slide">
                                <div class="carousel-caption">
                                    
                                </div>
                            </div>
                            <div class="item">
                                <img src="http://placehold.it/900x300/3c8dbc/ffffff&text=Размещайте проекты!" alt="Second slide">
                                <div class="carousel-caption">
                                    
                                </div>
                            </div>
                            <div class="item">
                                <img src="http://placehold.it/900x300/f39c12/ffffff&text=Отмечайте задачи!" alt="Third slide">
                                <div class="carousel-caption">
                                    
                                </div>
                            </div>
                        </div>
                        <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                            <span class="fa fa-angle-left"></span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                            <span class="fa fa-angle-right"></span>
                        </a>
                    </div>
                </div> <!-- /.box-body -->
            </div> <!-- /.box -->


            <!-- DIRECT CHAT PRIMARY -->
            <div class="box box-success direct-chat direct-chat-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Последние события</h3>

                    <div class="box-tools pull-right">
                        <span data-toggle="tooltip" title="3 New Messages" class="badge bg-light-blue"></span>
                    </div>
                </div>  <!-- /.box-header -->
                <div class="box-body">
                    <!-- Conversations are loaded here -->
                    <div class="direct-chat-messages">
                        @foreach($notes['events'] as $event)
                            <div class="direct-chat-msg {{ $event->position==1 ? 'right' : ''}}">
                                <div class="direct-chat-info clearfix">
                                    <span class="direct-chat-name {{ $event->position==1 ? 'pull-right' : 'pull-left'}}">{{ isset($event->user_name) ? $event->user_name : 'Гость' }}</span>
                                    <span class="direct-chat-timestamp {{ $event->position==1 ? 'pull-left' : 'pull-right'}}">{{ \Carbon\Carbon::parse($event->created_at)->format('d.m.Y H:i') }}</span>
                                </div> <!-- /.direct-chat-info -->
                                <img class="direct-chat-img" src="{{ isset($event->logo) ? Storage::url($event->logo) : '/img/noname.png' }}" alt="User Logo"><!-- /.direct-chat-img -->
                                <div class="direct-chat-text">
                                    {{ $event->title . ' ' . str_limit($event->name, 30) }}
                                </div>  <!-- /.direct-chat-text -->
                            </div>
                        @endforeach                        
                    </div> <!--/.direct-chat-messages-->
                </div> <!-- /.box-body -->
            </div>

            
        </div> <!-- /.left col -->

        <div class="col-md-6">

            <div class="row">
                <div class="col-lg-6 col-xs-12">
                </div>
                <div class="col-lg-6 col-xs-12">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <p>
                                <strong>Проектов</strong> 
                            </p>
                            <h3>{{ $notes['allProjects'] }}</h3>
                            @can('user-valid')
                                <p>
                                    <small>Ваших проектов: <strong>{{ $notes['userProjects'] }}</strong></small>
                                </p>
                            @endcan
                        </div>
                        <div class="icon">
                            <i class="fa fa-gears"></i>
                        </div>
                        @can('user-valid')
                            <a href="/projects/add" class="small-box-footer">
                                Добавить проект <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">Краткие правила</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <dl class="dl-horizontal">
                    <dt>Без регистрации</dt>
                    <dd>Доступна общая информация о системе</dd>
                    <dd>Вы можете получить информацию о приложении</dd>
                    <dt>&nbsp;</dt>
                    <dd>&nbsp;</dd>
                    <dd><strong>Для входа в систему необходимо зарегистрироваться</strong></dd>
                    <dt>По умолчанию</dt>
                    <dd>Вновь зарегистрированный пользователь должен быть подтвержден администратором</dd>
                    <dd>До этого доступен просмотр списка проектов, менеджеров и написание сообщений администратору</dd>
                    <dd>Подробно заполните свой профиль</dd>
                    <dt>После активации</dt>
                    <dd>Вы сможете добавлять проекты, получать информацию о проектах других менеджеров</dd>
                    <dd>Получать и писать отзывы, получать оповещения</dd>
                    </dl>
                </div> <!-- /.box-body -->
            </div> <!-- /.box -->



        </div> <!-- /.right col -->
      </div> <!-- /.row -->
@stop