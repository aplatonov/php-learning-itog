@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>401 Unauthorized</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> PM helper</a></li>
    <li class="active"> Ошибка 401</li>
</ol>
@stop

@section('content')
<div class="row">
   
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> 401</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Эх! Проблемы с авторизацией.</h3>

          <p>
            Возможно истекло время сессии. Попробуйте <a href="/login">залогиниться снова</a>.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
   
    
</div>
<!-- /.row -->
@stop
