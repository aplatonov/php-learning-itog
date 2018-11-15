@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Оповещения</small></h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> Администратор</a></li>
    <li class="active"> Оповещения</li>
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
    <div class="col-md-12">
        <div class="box">
            <div class="box-header">
                &nbsp;
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 300px;">
                        <form method="GET" action="{{ Route::current()->getName() }}" id="searchForm" style="display:none;">
                            <input type="hidden" name="searchText" value="{{ Request::get('searchText') }}" id="searchText">
                        </form>
                        <!--input class="form-control pull-right" name="searchTextVisible" id="searchTextVisible" placeholder="поиск по названию" type="text" value="{{ Request::get('searchText') }}"-->
                            <select id="searchTextVisible" name="searchTextVisible" class="form-control" name="speciality_id">
                                <option value="0">-- Фильтр по категории оповещения --</option>
                                @foreach($data['notesCategory'] as $category)
                                    @if ($category->id == Request::get('searchText'))
                                        <option selected value="{{$category->id}}">{{$category->name}}</option>
                                    @else
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit" onclick = "document.getElementById('searchText').value=document.getElementById('searchTextVisible').value; document.getElementById('searchForm').submit();">
                                    <i class="fa fa-search pull-right"></i>
                            </button>
                        </div>
                    </div>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-hover table-condensed">

                    <tr>
                        <th class="text-center"><a href="?page={{ $data['notes']->currentPage() }}&order=id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Код</a>{!! $data['page_appends']['order'] == 'id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['notes']->currentPage() }}&order=created_at&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Дата</a>{!! $data['page_appends']['order'] == 'created_at' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['notes']->currentPage() }}&order=note_category_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Категория</a>{!! $data['page_appends']['order'] == 'note_category_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['notes']->currentPage() }}&order=note_name&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Оповещение</a>{!! $data['page_appends']['order'] == 'note_name' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['notes']->currentPage() }}&order=from_user_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">От кого</a>{!! $data['page_appends']['order'] == 'from_user_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['notes']->currentPage() }}&order=to_user_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Кому</a>{!! $data['page_appends']['order'] == 'to_user_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-right"></th>
                    </tr>

                    @foreach($data['notes'] as $note)
                        <tr> 
                            <td class="text-center">{{ $note->id }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($note->created_at)->format('d.m.Y H:i') }}
                            </td>
                            <td class="text-center">{{ $note->category->name }}</td>
                            <td style="width: 300px;">
                                @if (in_array($note->note_category_id, [5,6,7]))
                                    <div class="box box-default collapsed-box">
                                        <div class="box-header no-border">
                                            {{ $note->note_name }}
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <small>{{ $note->description }}</small>
                                        </div>
                                    </div>
                                @elseif (in_array($note->note_category_id, [3,4]))
                                    <a href="{{ isset($note->link) ? url($note->link) : null }}">{{ $note->note_name }}</a>
                                @elseif (in_array($note->note_category_id, [2]))
                                    <a href="{{ isset($note->to) ? url('/users/edit/'.$note->to_user_id) : 'null'  }}">{{ isset($note->to) ? $note->to->name : null }}</a>
                                @endif
                            </td>
                            <td class="text-center">{!! isset($note->from) ? '<a href=\'/users/edit/'.$note->from->id.'\'>': '' !!}
                                {{ isset($note->from) ? $note->from->name : $note->link }}
                                {!! isset($note->from) ? '</a>': '' !!}</td>
                            <td class="text-center">{!! isset($note->to) ? '<a href=\'/users/edit/'.$note->to->id.'\'>': '' !!}
                                {{ isset($note->to) ? $note->to->name : null }}</td>
                                {!! isset($note->to) ? '</a>': '' !!}
                            <td class="text-center">
                                @if (Auth::user()->isAdmin())
                                    <form method="POST" action="{{action('NotesController@destroyNote',['id'=>$note->id])}}">
                                        <input type="hidden" name="_method" value="delete"/>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                        <input type="submit" class="btn btn-xs btn-danger" value="Удалить"/>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </table>
            </div> <!-- box body -->
            <div class="box-footer clearfix">
                <div class="box-tools pull-right">
                    {!! $data['notes']->appends($data['page_appends'])->links('vendor.pagination.default') !!}
                </div>
            </div><!-- /.box footer -->
        </div> <!-- /.box -->
    </div> <!-- /.col -->
</div> <!-- /.row -->
@stop
