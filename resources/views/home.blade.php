@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
    <h1>О приложении</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
        <!--li><a href="#">Forms</a></li-->
        <li class="active">О приложении</li>
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
        <div class="col-xs-8">
            <div class="box box-success">
                <div class="box-body">
                    {!! nl2br($settings->how_it_works_1 ?: '') !!}
                </div> <!-- /.box-body -->
            </div> <!-- /.box -->
        </div>
        <div class="col-xs-4">
            <div class="box box-success">
                <div class="box-body">
                    {!! nl2br($settings->how_it_works_2) !!}
                </div> <!-- /.box-body -->
            </div> <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
@stop