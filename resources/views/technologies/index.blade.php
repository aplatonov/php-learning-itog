@extends('adminlte::page')

@section('title', config('app.name', 'PM helper'))

@section('content_header')
    <h1>Технологии</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
        <li><a href="##"> Администратор</a></li>
        <li class="active"> Технологии</li>
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
            <div class="box">
                <div class="box-header">
                    <a href="{{route('technologies.create')}}">
                        <button class="btn btn-primary btn-sm pull-left" type="button"><i class="fa fa-plus pull-left"></i>Добавить</button>
                    </a>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-striped table-hover table-condensed">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Название</th>
                            <th class="text-center">Статус</th>
                            <th class="text-center">Действия</th>
                        </tr>
                        @foreach($technologies as $technology)
                            <tr>
                                <td class="text-center">{{ $technology->id }}</td>
                                <td class="text-left">{{ $technology->name }}</td>
                                <td class="text-center"><span class="label label-default">{{ $technology->active ? 'опубликовано' : 'скрыто от показа'}}</span></td>
                                <td class="text-center">
                                    <form class="" action="{{route('technologies.destroy',$technology->id)}}" method="post">
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <a href="{{route('technologies.edit',$technology->id)}}" class="btn btn-info btn-xs">Редактировать</a>
                                        <input type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Вы уверены, что хотите удалить запись?');" name="name" value="Удалить">
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">

                </div>
            </div><!-- /.box -->
        </div>
    </div>
@stop