@extends('adminlte::page')

@section('title', config('app.name', 'PM helper'))

@section('content_header')
    <h1>Просмотр проекта</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
        <li><a href="/projects"> Проекты</a></li>
        <li class="active"> Просмотр проекта</li>
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
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Информация о проекте</h3>
                </div><!-- /.box-header -->
                <form role="form" method="POST" enctype="multipart/form-data" action="{{action('ProjectsController@storeProject')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="isUpdate" value="1">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                    <input type="hidden" name="owner_id" value="{{ $project->owner_id }}">
                    <div class="box-body">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Основная информация</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group col-xs-12">
                                            <label for="project_name">Название проекта</label>
                                            <input type="text" class="form-control" id="project_name" name="project_name" value="{{ $project->project_name }}">
                                            @if ($errors->has('project_name'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('project_name') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group col-xs-12">
                                            <label for="description">Описание проекта</label>
                                            <textarea name="description" id="description" class="form-control" placeholder="Введите описание проекта" rows="3">{{ $project->description }}</textarea>

                                            @if ($errors->has('description'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('description') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group col-xs-12">
                                            <label>Укажите специализацию (направление) проекта</label>
                                            <select id="speciality_id" name="speciality_id" class="form-control" name="speciality_id">
                                                <option value="0">Направление проекта</option>
                                                @foreach($specialities as $speciality)
                                                    @if ($speciality->id == $project->speciality_id)
                                                        <option selected value="{{$speciality->id}}">{{$speciality->name}}</option>
                                                    @else
                                                        <option value="{{$speciality->id}}">{{$speciality->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @if ($errors->has('speciality_id'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('speciality_id') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="start_date">Срок начала</label>
                                            <input type="text" class="form-control" id="start_date" name="start_date" value="{{ $project->start_date }}">
                                            @if ($errors->has('start_date'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('start_date') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="finish_date">Окончание</label>
                                            <input type="text" class="form-control" id="finish_date" name="finish_date" value="{{ $project->finish_date }}">
                                            @if ($errors->has('finish_date'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('finish_date') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                       
                                        <div class="form-group col-xs-12">
                                             <p class="form-control-static">
                                                @if ($project->doc)
                                                    Документация <span class="label label-success">Есть!&nbsp;</span>
                                                    <a href="{{ isset($project->doc) ? Storage::url($project->doc) : '' }}">Посмотреть</a>
                                                @endif
                                                <br>
                                                <label for="doc">Заменить документацию <small>(pdf, rtf, doc)</small></label>
                                                <input id="doc" type="file" name="doc" value="{{ $project->doc }}" accept="*.pdf,*.rtf,*.doc">
                                            </p>
                                             @if ($errors->has('doc'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('doc') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group col-xs-6">
                                            <div class="checkbox">
                                                <br>
                                                <label for="active">
                                                    <input type="checkbox" id="active" name="active" {{ $project->active ? 'checked' : ''}} value="1">&nbsp;Показать на сайте</label>
                                            </div>
                                            @if ($errors->has('active'))
                                                <span class="text-danger">
                                                    <strong><small>{{ $errors->first('active') }}</small></strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!-- right column -->
                            <div class="col-md-6">
                                <div class="box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Укажите технологии, необходимые для проекта</h3>
                                        @if ($errors->has('technologies'))
                                            <br>
                                            <span class="text-danger">
                                                    <strong><small>{{ $errors->first('technologies') }}</small></strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive mailbox-messages">
                                            <table class="table table-hover table-striped table-condensed">
                                                <tbody>
                                                    @forelse($technologies as $technology)
                                                        @if($loop->iteration % 3 == 1)
                                                            <tr>
                                                        @endif
                                                            <td><input type="checkbox" id="technology{{$technology->id}}" name="technologies[{{$technology->id}}]" {{ in_array($technology->id, (array) $project->technologies) ? 'checked' : ''}} value="{{ $technology->id }}"></td>
                                                            <td class="mailbox-name">{{ $technology->name }}</td>
                                                        @if($loop->iteration % 3 == 0)
                                                            <tr>
                                                        @endif
                                                    @empty
                                                        <tr>
                                                            <td span="4">Не внесены технологии</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            <!-- /.table -->
                                        </div>
                                    </div>
                                </div>

                                <div class="box">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Чек-лист проекта</h3>
                                        @if ($errors->has('project_marks'))
                                            <br>
                                            <span class="text-danger">
                                                <strong><small>{{ $errors->first('project_marks') }}</small></strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="box-body data-checklist-container">
                                        <div class="form-group col-xs-12 data-checklist-row hidden">
                                            <div class="input-group col-xs-12">
                                                    <span class="input-group-addon">
                                                        <input type="checkbox" name="mark_done[]">
                                                    </span>
                                                <input class="form-control" type="text" name="mark_name[]">
                                                <input class="form-control" type="text" id="mark_finish_date" name="mark_finish_date[]">
                                            </div>
                                        </div>
                                        @foreach ($marks as $mark)
                                            <div class="form-group col-xs-12 data-checklist-row-visible">
                                                <div class="input-group col-xs-12">
                                                    <span class="input-group-addon">
                                                        <input type="checkbox" id="mark_done{{ $mark->id }}" name="mark_done[{{ $mark->id }}]" {{ $mark->is_done ? 'checked' : ''}} value="{{ $mark->id }}">
                                                    </span>
                                                    <input class="form-control" type="text" id="mark_name{{ $mark->id }}" name="mark_name[{{ $mark->id }}]" value="{{ $mark->name }}">
                                                    <input class="form-control" type="text" id="mark_finish_date{{ $mark->id }}" name="mark_finish_date[{{ $mark->id }}]" value="{{ $mark->finish_date }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="box-footer with-border">
                                        <button type="button" class="btn btn-xs btn-info pull-right" onclick="addNewTask()">Добавить пункт</button>
                                    </div>
                                </div>
                            </div>
                            <!-- right column END -->
                        </div>
                        <div class="box-footer clearfix">
                            <a href="{{ Redirect::back()->getTargetUrl() }}"><button type="button" class="btn btn-default">Отмена</button></a>
                            <button type="submit" class="btn btn-success pull-right" onclick="document.getElementById('preloader').style.display = 'block'">Сохранить проект</button>
                        </div>
                    </div><!-- /.box-body -->
                </form>
                <div class="overlay" id="preloader" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
@stop

@section('jscripts')
    <script type="text/javascript">
        $(function () {
            $('[id*="date"]').datepicker({
                format: "yyyy-mm-dd",
                weekStart: 1,
                autoClose: true
            });
        });
    </script>

    <script type="text/javascript">
        function addNewTask() {
            $row = $(".data-checklist-row")
                .clone()
                .removeClass("hidden")
                .removeClass("data-checklist-row")
                .addClass("data-checklist-row-visible");
            $row.find("#mark_finish_date")
                .attr('id', "mark_finish_date_" + (+$(".data-checklist-row-visible").length + 1))
                .datepicker({
                    format: "yyyy-mm-dd",
                    weekStart: 1,
                    autoClose: true
                });
            $row.appendTo(".data-checklist-container");
        }
    </script>
@endsection
