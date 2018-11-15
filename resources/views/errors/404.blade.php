@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>404 Page not found</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> PM helper</a></li>
    <li class="active"> Ошибка 404</li>
</ol>
@stop

@section('content')
<div class="row">
   
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Упс! Страница не найдена.</h3>

          <p>
            Мы не нашли страницу, которую вы ищите.
            Вы можете <a href="/home">вернуться к начальной странице</a> и попробовать снова.
          </p>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
   
    
</div>
<!-- /.row -->
@stop
