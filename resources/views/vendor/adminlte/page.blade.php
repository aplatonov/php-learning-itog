@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
            <nav class="navbar navbar-static-top">
                <div class="container">
                    <div class="navbar-header">
                        <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                        <ul class="nav navbar-nav">
                            @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                        </ul>
                    </div>
                    <!-- /.navbar-collapse -->
            @else
            <!-- Logo -->
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a>
            @endif
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Вход</a></li>
                            <li><a href="{{ route('register') }}">Регистрация</a></li>
                        @else
                            <!-- Messages: style can be found in dropdown.less-->
                            <li class="dropdown messages-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-envelope-o"></i>
                                    <span class="label label-success">{{ count($notes['forms'])>0 ? $notes['forms']->sum('notes_count') : '' }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header"><strong>{{ count($notes['forms'])>0 ? $notes['forms']->sum('notes_count') : 'нет' }} новых отзывов и сообщений</strong></li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            @foreach($notes['forms'] as $noteForm)
                                                <li><!-- start message -->
                                                    <a href="##">
                                                        <p><h5><span class="badge">{{$noteForm->notes_count}}</span>&nbsp;нов. {{$noteForm->name}}</h5></p>
                                                        <h4>&nbsp;
                                                            <small><i class="fa fa-clock-o"></i> {{ isset($noteForm->note_last) ? 'последний '.\Carbon\Carbon::parse($noteForm->note_last)->format('в H:i d.m.Y') : '' }}</small>
                                                        </h4>
                                                    </a>
                                                </li> <!-- end message -->
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="{{ Auth::user()->isAdmin() ? '/admin/notes' : '/userNotes' }}">Посмотреть все отзывы и сообщения</a></li>
                                </ul>
                            </li>
                            <!-- Notifications -->
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="label label-warning">{{ $notes['sumNotif']>0 ? $notes['sumNotif'] : '' }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="header"><strong>{{ $notes['sumNotif']>0 ? $notes['sumNotif'] : 'нет' }} новых оповещений</strong></li>
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            @if (Auth::user()->isAdmin())
                                                @if ($notes['newUsers']>0)
                                                    <li>
                                                        <a href="/admin/users"><i class="fa fa-bookmark text-gray"></i>
                                                           Новые пользователи - <span class="badge">{{ $notes['newUsers'] }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if ($notes['newProjects']>0)
                                                    <li>
                                                        <a href="/projects"><i class="fa fa-gears text-gray"></i>
                                                           Новые проекты - <span class="badge">{{ $notes['newProjects'] }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endif
                                            @foreach ($notes['notif'] as $notif)
                                                <li>
                                                    <a href="##"><i class="fa fa-exchange text-gray"></i>
                                                       {{$notif->name}} - <span class="badge">{{ $notif->notes_count }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <li class="footer"><a href="{{ Auth::user()->isAdmin() ? '/admin/notes' : '/userNotes' }}">Посмотреть все оповещения</a></li>
                                </ul>
                            </li>
                        
                            <!-- User Account -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ isset(Auth::user()->logo) ? Storage::url(Auth::user()->logo) : '/img/noname.png' }}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ Auth::user()->name }} [{{ Auth::user()->login }}]</span>
                                </a>
                                <ul class="dropdown-menu">
                                  <!-- User image -->
                                  <li class="user-header">
                                    <img src="{{ isset(Auth::user()->logo) ? Storage::url(Auth::user()->logo) : '/img/noname.png' }}" class="img-circle" alt="User Image">
                                    <p>
                                      {{ Auth::user()->name }}<br>{{ Auth::user()->contact_person}}
                                      <small>{{ isset(Auth::user()->created_at) ? 'зарегистрирован ' . Carbon\Carbon::parse(Auth::user()->created_at)->format('d-m-Y') : '' }}</small>
                                    </p>
                                  </li>
                                  <!-- Menu Body -->
                                  <li class="user-body">
                                    <div class="row">
                                      <div class="col-xs-4 text-center">
                                        <a href="/userProjects"><small class="label bg-blue">Проекты</small></a>
                                      </div>
                                      <div class="col-xs-4 text-center">
                                        <a href="/userNotes"><small class="label bg-red">Сообщен.</small></a>
                                      </div>
                                    </div>
                                    <!-- /.row -->
                                  </li>
                                  <!-- Menu Footer-->
                                  <li class="user-footer">
                                    <div class="pull-left">
                                      <a href="/users/editprofile" class="btn btn-default btn-flat">Профиль</a>
                                    </div>
                                    <div class="pull-right">
                                        <!--a href="#" class="btn btn-default btn-flat">Выход</a-->
                                        @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                            <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" class="btn btn-default btn-flat">
                                            {{ trans('adminlte::adminlte.log_out') }}
                                            </a>
                                        @else
                                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">
                                            {{ trans('adminlte::adminlte.log_out') }}</a>
                                            <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                            @if(config('adminlte.logout_method'))
                                                {{ method_field(config('adminlte.logout_method')) }}
                                            @endif
                                            {{ csrf_field() }}
                                            </form>
                                        @endif
                                    </div>
                                  </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <div class="row">
                <div class="col-md-3">
                  <img class="img-responsive" src="/img/logo_full.png" alt="PM helper logo">
                  <!-- /.box -->
                </div>

                <div class="col-md-3">
                    <ul class="list-unstyled">
                        <li>Итоговая работа Платонова А.В.</a></li>
                        <li>курс "Продвинутый PHP"</li>
                        <li>компании Mediasoft, 2018</li>
                    </ul>
                </div>
                <!-- ./col -->
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>Работает на</li>
                        <li><a href="https://laravel.com">Laravel - The PHP Framework For Web Artisans</a></li>
                        <li><a href="https://adminlte.io">Панель администрирования AdminLTE</a></li>
                    </ul>
                </div>
                <!-- ./col -->
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/app.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
