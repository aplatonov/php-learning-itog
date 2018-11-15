@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Раздел администратора</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> Администратор</a></li>
    <li class="active"> Управление менеджерами</li>
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
                <h3 class="box-title">Управление менеджерами</h3>
                <div class="box-tools">
                    <form class="form-inline" method="GET" action="{{url('/admin/users')}}">
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <input type="text" class="form-control pull-right" name="searchText" value="{{ Request::get('searchText') }}" placeholder="login, имя, телефон или email">

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
                        <th><a href="?page={{ $data['users']->currentPage() }}&order=id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">ID</a>{!! $data['page_appends']['order'] == 'id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['users']->currentPage() }}&order=login&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Login</a>{!! $data['page_appends']['order'] == 'login' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['users']->currentPage() }}&order=name&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Имя</a>{!! $data['page_appends']['order'] == 'name' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th><a href="?page={{ $data['users']->currentPage() }}&order=phone&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Телефон, e-mail</a>{!! $data['page_appends']['order'] == 'phone' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['users']->currentPage() }}&order=role_id&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Роль</a>{!! $data['page_appends']['order'] == 'role_id' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}<br><span class="text-muted"><small>(кликните, чтобы изменить)</small></span></th>
                        <th class="text-center"><a href="?page={{ $data['users']->currentPage() }}&order=confirmed&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Подтвержден</a>{!! $data['page_appends']['order'] == 'confirmed' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}</th>
                        <th class="text-center"><a href="?page={{ $data['users']->currentPage() }}&order=valid&dir={{ $data['dir'] ? $data['dir'] : 'asc' }}{{ $data['searchText'] ? '&searchText='.$data['searchText'] : '' }}">Блокировка</a>{!! $data['page_appends']['order'] == 'valid' ? $data['dir'] == 'desc' ? '<span class="glyphicon glyphicon-arrow-down"></span>' : '<span class="glyphicon glyphicon-arrow-up"></span>' : '' !!}<br><span class="text-muted"><small>(кликните, чтобы изменить)</small></span></th>
                        <th class="text-center"></th>
                    </tr>

                    @foreach($data['users'] as $user)
                        <tr> 
                            <td><a href="/users/edit/{{ $user->id }}">{{ $user->id }}</a></td>
                            <td><a href="/users/edit/{{ $user->id }}">{{ $user->login }}</a></td>
                            <td><a href="/users/edit/{{ $user->id }}">{{ $user->name }}<br><small>{{ $user->contact_person }}</small></a></td>
                            <td><a href="/users/edit/{{ $user->id }}">{{ $user->phone }}<br>{{ $user->email }}</a></td>
                            <td class="text-center">
                                @if ($user->isAdmin())
                                    <div id="adminUser{{ $user->id }}">
                                        <form class="adminUser" > 
                                            <input class="btn btn-xs btn-default" type="submit" name="action" id="adminedUser{{ $user->id }}" value="Администратор">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                                        </form>
                                    </div>
                                @else
                                    <div id="adminUser{{ $user->id }}">
                                        <form class="adminUser" > 
                                            <input class="btn btn-xs btn-default" type="submit" name="action" id="adminedUser{{ $user->id }}" value="Пользователь">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                                        </form>
                                    </div>
                                @endif                                
                            </td>
                            <td class="text-center">
                                @if (!$user->confirmed)
                                    <div id="confirmedUser{{ $user->id }}">
                                        <form class="confirmUser" > 
                                            <input class="btn btn-xs btn-default" type="submit" value="Подтвердить">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                                        </form>
                                    </div>
                                @else
                                    <div>да</div>
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($user->valid)
                                    <div id="blockUser{{ $user->id }}">
                                        <form class="blockUser" > 
                                            <input class="btn btn-xs btn-default" type="submit" name="action" id="blockedUser{{ $user->id }}" value="Заблокировать">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                                        </form>
                                    </div>
                                @else
                                    <div id="blockUser{{ $user->id }}">
                                        <form class="blockUser" > 
                                            <input class="btn btn-xs btn-default" type="submit" name="action" id="blockedUser{{ $user->id }}" value="Разблокировать">
                                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}"/>
                                        </form>
                                    </div>
                                @endif
                            <td class="text-center">
                                @if (Auth::user()->isAdmin() && $user->id !=1 )
                                    <form action="{{action('AdminController@deleteUser',['id'=>$user->id])}}" method="POST">
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-danger btn-xs" onclick="if (confirm('Вы уверены, что хотите удалить запись?')) {document.getElementById('preloader').style.display = 'block'; return true;} else {return false;}" name="name" value="Удалить">
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </table>
            </div> <!-- /.box-body -->


            <div class="box-footer">
                {!! $data['users']->appends($data['page_appends'])->links('vendor.pagination.default') !!}
            </div> <!-- /.box-footer -->
            <div class="overlay" id="preloader" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div> <!-- /.box -->
    </div> <!-- /.col -->
</div>
<!-- /.row -->
@stop

@section('jscripts')
    <script>
        $(document).ready(function(){
            $('.confirmUser').submit(function(e){
                e.preventDefault();

                var user_id = $(this).find("input[name=user_id]").val();
    
                $.ajax({
                    type: 'POST',
                    url: '/admin/users/confirm/' + user_id,
                    cache: false,
                    dataType: 'json',
                    data: {user_id: user_id,
                           '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            $("#confirmedUser"+user_id).html('да');
                        } else {
                            console.log(response.text + ' Не хватает прав для подтверждения пользователя.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('oshibka');
                    }

                });
                return false;
            });

            $('.blockUser').submit(function(e){
                e.preventDefault();
                var action = $(this).find("input[name=action]").val();
                var user_id = $(this).find("input[name=user_id]").val();
                if (action == 'Заблокировать') {
                    action = 0;
                } else {
                    action = 1;
                }
                $.ajax({
                    type: 'POST',
                    url: '/admin/users/block/' + user_id,
                    cache: false,
                    dataType: 'json',
                    data: {user_id: user_id,
                           action: action,
                           '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            if (action == 0) {
                                document.getElementById("blockedUser"+user_id).value = "Разблокировать";   
                            } else {
                                document.getElementById("blockedUser"+user_id).value = "Заблокировать";
                            }
                            
                            console.log("#blockedUser"+user_id);
                        } else {
                            console.log(response.text + ' Не хватает прав для блокировки/разблокировки пользователя.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('oshibka');
                    }
                });
                return false;
            });            

            $('.adminUser').submit(function(e){
                e.preventDefault();
                var action = $(this).find("input[name=action]").val();
                var user_id = $(this).find("input[name=user_id]").val();
                if (action == 'Пользователь') {
                    action = 1;
                } else {
                    action = 2;
                }
                console.log(action+'-'+user_id);
                $.ajax({
                    type: 'POST',
                    url: '/admin/users/role/' + user_id,
                    cache: false,
                    dataType: 'json',
                    data: {user_id: user_id,
                           action: action,
                           '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            if (action == 1) {
                                document.getElementById("adminedUser"+user_id).value = "Администратор";   
                            } else {
                                document.getElementById("adminedUser"+user_id).value = "Пользователь";
                            }
                            
                            console.log("#adminedUser"+user_id);
                        } else {
                            console.log(response.text + ' Не хватает прав для изменения роли пользователя.');
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