@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>403 Authentication failed</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> PM helper</a></li>
    <li class="active"> Ошибка 403</li>
</ol>
@stop

@section('content')
<div class="row">
   
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> 403</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Ой! Вам не хватает прав.</h3>

          <p>
            Для доступа к ресурсу, к которому Вы обратились, требуются особые права.
            Вы можете <a href="/home">вернуться к начальной странице</a> и попробовать снова.
            <br>
            @if (!Auth::guest())
              Также Вы можете <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">выйти из системы</a> и зайти пользователем с соответствующими правами.
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            @endif
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
   
    
</div>
<!-- /.row -->
@stop
