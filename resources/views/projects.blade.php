@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Проекты <small>{{ isset($data['title']) ? $data['title'] : ''}}</small></h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> {{ isset($data['title']) ? ' Личный кабинет' : 'PM helper'}}</a></li>
    <li class="active"> Проекты</li>
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
                <form method="GET" action="{{ url('/projects/add') }}" id="addProjectForm" style="display:none;">
                    <input type="hidden" name="fromPage" value="{{ Route::current()->getName() }}" id="fromPage">
                </form>
                &nbsp;
                @can('user-valid')
                    <button class="btn btn-primary btn-sm pull-left" type="submit" onclick = "document.getElementById('addProjectForm').submit();"><i class="fa fa-user-plus pull-left"></i>Добавить проект</button>
                @endcan
                <div class="box-tools">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <form method="GET" action="{{ Route::current()->getName() }}" id="searchForm" style="display:none;">
                            <input type="hidden" name="searchText" value="{{ Request::get('searchText') }}" id="searchText">
                        </form>
                        <input class="form-control pull-right" name="searchTextVisible" id="searchTextVisible" placeholder="поиск по названию" type="text" value="{{ Request::get('searchText') }}" onkeyup="if (event.keyCode == 13) document.getElementById('searchButton').click();">
                        <div class="input-group-btn">
                            <button class="btn btn-default btn-sm" type="submit" id="searchButton" onclick = "document.getElementById('searchText').value=document.getElementById('searchTextVisible').value; document.getElementById('searchForm').submit();">
                                    <i class="fa fa-search pull-right"></i>
                            </button>
                        </div>
                    </div>
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-hover table-condensed">

                    <tr>
                        <th class="text-center"><a href="?page={{ $data['projects']->currentPage() }}&order=id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Код</a>{!! $data['page_appends']['order'] == 'id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['projects']->currentPage() }}&order=project_name&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Название</a>{!! $data['page_appends']['order'] == 'project_name' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th>&nbsp;</th>
                        <th class="text-center"><a href="?page={{ $data['projects']->currentPage() }}&order=start_date&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Сроки</a>{!! $data['page_appends']['order'] == 'start_date' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['projects']->currentPage() }}&order=speciality_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Направление</a>{!! $data['page_appends']['order'] == 'speciality_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center">Конт. инф.</th>
                        <th class="text-center">Проект</th>
                        <th class="text-center"></th>
                    </tr>

                    @foreach($data['projects'] as $project)
                        <tr> 
                            <td class="text-center">{{ $project->id }}</td>
                            <td>
                                @if(Auth::user()->isAdmin() || Auth::user()->id == $project->owner_id)
                                    <a href="/projects/{{ $project->id }}/edit">{{ $project->project_name }}</a>
                                @else
                                    {{ $project->project_name }}
                                @endif
                                <br>
                                <small>{{ str_limit($project->description,60) }}</small>
                                <br>
                                <small>
                                    @forelse ($project->projectTechnologies as $technology)
                                        <span class="label label-success">{{ $technology->name }}&nbsp;</span>
                                        @if ($loop->index == 2)
                                                <span class="badge">всего: {{ $loop->count }}</span>
                                            @break;
                                        @endif
                                    @empty
                                        
                                    @endforelse
                                </small>
                            </td>
                            <td class="mailbox-attachment">
                                @if (!Auth::guest() && Auth::user()->confirmed == 1 && Auth::user()->valid == 1 && $project->doc)
                                    <a href="{{ isset($project->doc) ? Storage::url($project->doc) : '' }}"><i class="fa fa-paperclip"></i></a>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ isset($project->start_date) ? 'c '.\Carbon\Carbon::parse($project->start_date)->format('d.m.Y') : '&nbsp;' }}{{ isset($project->finish_date) ? ' до '.\Carbon\Carbon::parse($project->birth_date)->format('d.m.Y') : '&nbsp;' }}
                            </td>
                            <td class="text-center">{{ isset($project->speciality_id) ? $project->speciality->name : '' }}</td>
                            <td class="text-center">
                                @if (!Auth::guest() && Auth::user()->confirmed == 1 && Auth::user()->valid == 1)
                                    <div id="projectInfo{{ $project->id }}">
                                        <form class="showProjectInfo" > 
                                            <input class="btn btn-xs" type="submit" value="Показать">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}"/>
                                        </form>
                                    </div>
                                @else
                                    <div><span class="badge badge-warning">Нет прав</span></div>
                                @endif
                            </td>

                            <td class="text-center">
                                @if (Auth::user()->id == $project->owner_id || Auth::user()->isAdmin())
                                    @if ($project->active)
                                        <div id="confirmProject{{ $project->id }}">
                                            <form class="confirmProject" >
                                                <input class="btn btn-xs" type="submit" name="action" id="confirmedProject{{ $project->id }}" value="Скрыть">
                                                <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                                <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}"/>
                                            </form>
                                        </div>
                                    @else
                                        <div id="confirmProject{{ $project->id }}">
                                            <form class="confirmProject" >
                                                <input class="btn btn-xs" type="submit" name="action" id="confirmedProject{{ $project->id }}" value="Опубликовать">
                                                <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                                <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}"/>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <span class="label label-default">{{ $project->active ? 'опубликовано' : 'скрыт от показа'}}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (Auth::user()->id == $project->owner_id || Auth::user()->isAdmin())
                                    <form action="{{action('ProjectsController@deleteProject',['id'=>$project->id])}}" method="POST">
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-danger btn-xs" onclick="if (confirm('Вы уверены, что хотите удалить запись?')) {document.getElementById('preloader').style.display = 'block'; return true;} else {return false;}" name="name" value="Удалить">
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach



                </table>
            </div> <!-- box body -->
            <div class="box-footer clearfix">
                <div class="box-tools pull-right">
                    {!! $data['projects']->appends($data['page_appends'])->links('vendor.pagination.default') !!}
                </div>
            </div><!-- /.box footer -->
            <div class="overlay" id="preloader" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div> <!-- /.box -->
    </div> <!-- /.col -->
</div> <!-- /.row -->
@stop

@section('jscripts')
    <script>
        $(document).ready(function(){
            $('.showProjectInfo').submit(function(e){
                e.preventDefault();

                var project_id = $(this).find("input[name=project_id]").val();
    
                $.ajax({
                    type: 'POST',
                    url: '/projects/info/' + project_id,
                    cache: false,
                    dataType: 'json',
                    data: {project_id: project_id,
                           '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            $("#projectInfo"+project_id).html(response.project_info);
                        } else {
                            console.log(response.text + ' Не хватает прав для получения информации о проекте.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('oshibka');
                    }
                });
                return false;
            });               
        });
    </script>

    <script>
        $(document).ready(function(){
            $('.confirmProject').submit(function(e){
                e.preventDefault();
                var action = $(this).find("input[name=action]").val();
                var project_id = $(this).find("input[name=project_id]").val();
                if (action == 'Скрыть') {
                    action = 0;
                } else {
                    action = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: '/projects/active/' + project_id,
                    cache: false,
                    dataType: 'json',
                    data: {project_id: project_id,
                        action: action,
                        '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            if (action == 0) {
                                document.getElementById("confirmedProject"+project_id).value = "Опубликовать";
                            } else {
                                document.getElementById("confirmedProject"+project_id).value = "Скрыть";
                            }
                        } else {
                            console.log(response.text + ' Не хватает прав для блокировки/разблокировки проекта.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('oshibka ' + errorThrown);
                    }
                });
                return false;
            });
        });
    </script>
@endsection