@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Управление отзывами</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> Администратор</a></li>
    <li class="active"> Управление отзывами</li>
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
        <div class="box box-success">

            <div class="box-header with-border">
                <form method="GET" action="{{ url('/admin/comments/add') }}" id="addCommentForm" style="display:none;">
                    <input type="hidden" name="fromPage" value="{{ Route::current()->getName() }}" id="fromPage">
                </form>
                <button class="btn btn-primary btn-sm pull-left" type="submit" onclick = "document.getElementById('addCommentForm').submit();"><i class="fa fa-user-plus pull-left"></i>Добавить отзыв</button>
                <div class="box-tools">
                    <form class="form-inline" method="GET" action="{{url('/admin/comments')}}">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" class="form-control pull-right" name="searchText" value="{{ Request::get('searchText') }}" placeholder="Поиск по автору и содержанию">

                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> <!-- /.box-header -->

            <div class="box-body table-responsive no-padding">
                <table class="table table-striped table-hover table-condensed">
                    <tr>
                        <th><a href="?page={{ $data['comments']->currentPage() }}&order=id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">ID</a>{!! $data['page_appends']['order'] == 'id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['comments']->currentPage() }}&order=author_name&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Автор и содержание</a>{!! $data['page_appends']['order'] == 'author_name' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['comments']->currentPage() }}&order=created_at&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Дата и время</a>{!! $data['page_appends']['order'] == 'created_at' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['comments']->currentPage() }}&order=company_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">В адрес менеджера</a>{!! $data['page_appends']['order'] == 'company_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['comments']->currentPage() }}&order=user_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">От</a>{!! $data['page_appends']['order'] == 'user_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['comments']->currentPage() }}&order=visible_on_main&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">На главной</a>{!! $data['page_appends']['order'] == 'visible_on_main' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['comments']->currentPage() }}&order=active&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Отзыв</a>{!! $data['page_appends']['order'] == 'active' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-right"></th>
                    </tr>

                    @foreach($data['comments'] as $comment)
                        <tr> 
                            <td><a href="/admin/comments/{{ $comment->id }}/edit">{{ $comment->id }}</a></td>
                            <td>
                                <a href="/admin/comments/{{ $comment->id }}/edit">{{ $comment->author_name }}
                                <br>
                                <span class="text-muted">{{ $comment->author_position }}</span>
                                <br>
                                <small class="text-muted">{{ str_limit($comment->description,80) }}</small>
                                <br>
                                </a>
                            </td>
                            <td>{{ isset($comment->created_at) ? \Carbon\Carbon::parse($comment->created_at)->format('d.m.Y H:i') : '' }}</td>
                            <td><a href="/users/edit/{{ $comment->company_id }}">{{ isset($comment->aboutUser) ? $comment->aboutUser->name : '' }}</a></td>
                            <td><a href="/users/edit/{{ $comment->user_id }}">{{ isset($comment->owner) ? $comment->owner->name : '' }}</a></td>
                            <td class="text-center">{!! $comment->visible_on_main ? '<span class="badge bg-green">да</span>' : '<span class="badge bg-red">нет</span>' !!}</td>
                            <td class="text-center">
                                @if ($comment->active)
                                    <div id="blockComment{{ $comment->id }}">
                                        <form class="blockComment"> 
                                            <input class="btn btn-xs btn-default" type="submit" name="action" id="blockedComment{{ $comment->id }}" value="Заблокировать">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="comment_id" id="comment_id" value="{{ $comment->id }}"/>
                                        </form>
                                    </div>
                                @else
                                    <div id="blockComment{{ $comment->id }}">
                                        <form class="blockComment" > 
                                            <input class="btn btn-xs btn-default" type="submit" name="action" id="blockedComment{{ $comment->id }}" value="Разблокировать">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="comment_id" id="comment_id" value="{{ $comment->id }}"/>
                                        </form>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{action('CommentsController@destroyComment',['id'=>$comment->id])}}">
                                    <input type="hidden" name="_method" value="delete"/>
                                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                                    <input type="submit" class="btn btn-xs btn-danger" value="Удалить"/>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </table>
            </div> <!-- /.box-body -->


            <div class="box-footer">
                {!! $data['comments']->appends($data['page_appends'])->links('vendor.pagination.default') !!}
            </div> <!-- /.box-footer -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@stop

@section('jscripts')
    <script>
        $(document).ready(function(){
            $('.blockComment').submit(function(e){
                e.preventDefault();
                var action = $(this).find("input[name=action]").val();
                var comment_id = $(this).find("input[name=comment_id]").val();
                if (action == 'Заблокировать') {
                    action = 0;
                } else {
                    action = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: '/admin/comments/block/' + comment_id,
                    cache: false,
                    dataType: 'json',
                    data: {comment_id: comment_id,
                           action: action,
                           '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            if (action == 0) {
                                document.getElementById("blockedComment"+comment_id).value = "Разблокировать";   
                            } else {
                                document.getElementById("blockedComment"+comment_id).value = "Заблокировать";
                            }
                            
                            console.log("#blockedComment"+comment_id);
                        } else {
                            console.log(response.text + ' Не хватает прав для блокировки/разблокировки комментария.');
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
@endsection