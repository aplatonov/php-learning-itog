@extends('adminlte::page')

@section('title', 'PM helper')

@section('content_header')
<h1>Компании</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard"></i> Главное меню</a></li>
    <li><a href="#"> PM helper</a></li>
    <li class="active"> Компании</li>
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
            <div class="box-body">
                <p>&nbsp;</p>
                @foreach($data['companies'] as $company)
                    <div class="attachment-block clearfix">
                        <img class="attachment-img" src="{{ isset($company->logo) ? Storage::url($company->logo) : '/img/noname.png' }}" alt=""></img>
                        <div class="attachment-pushed">
                            <h4 class="attachment-heading">{!! '<small>#' . $company->id . ' </small>' . $company->name !!}</h4>
                            <div class="attachment-text col-md-9">
                                {{ $company->description}}
                                <br><br>
                                <span class="badge bg-aqua">Проекты {{ count($company->projects) }}</span>
                                {!! count($company->comments->where('active', true))>0 ? '<span class="badge bg-red">Отзывы ' .count($company->comments->where('active', true)).'</span>' : '' !!}
                            </div>
                            <div class="attachment-text col-md-3" id="companyInfo{{ $company->id }}">
                            
                            </div>
                        </div>
                    </div>
                    @can('user-valid')
                        <form class="showCompanyInfo" id="infoForm"> 
                            <button type="submit" class="btn btn-default btn-xs pull-right"><i class="fa fa-envelope-o"></i> Конт. инфо</button>
                            <input type="hidden" name="_token"  id="_token" value="{{csrf_token()}}"/>
                            <input type="hidden" name="company_id" id="company_id" value="{{ $company->id }}"/>
                        </form>
                        <span class="pull-right text-muted">&nbsp;</span>

                        <form method="GET" action="{{ url('/comments/add') }}">
                            <input type="hidden" name="company_id" value="{{ $company->id}}" id="company_id">
                            <input type="hidden" name="fromPage" value="{{ Route::current()->getName() }}" id="fromPage">
                            <button type="submit" class="btn btn-default btn-xs pull-right"><i class="fa fa-comment-o"></i> Оставить отзыв</button>
                        </form>
                    @endcan
                    
    

                    <p>&nbsp;</p>
                @endforeach
            </div> <!-- box body -->
            <div class="box-footer clearfix">
                <div class="box-tools pull-right">
                    {!! $data['companies']->links('vendor.pagination.default') !!}
                </div>
            </div><!-- /.box footer -->
        </div> <!-- /.box -->
    </div> <!-- /.col -->
</div> <!-- /.row -->
@stop

@section('jscripts')
    <script>
        $(document).ready(function(){
            $('.showCompanyInfo').submit(function(e){
                e.preventDefault();

                var company_id = $(this).find("input[name=company_id]").val();

                console.log(company_id);
    
                $.ajax({
                    type: 'POST',
                    url: '/companies/info/' + company_id,
                    cache: false,
                    dataType: 'json',
                    data: {company_id: company_id,
                           '_token': "{{csrf_token()}}"
                    },
                    success: function (response) {
                        if (response.text == 'success') {
                            $("#companyInfo"+company_id).html(response.company_info);
                        } else {
                            console.log(response.text + ' Не хватает прав для получения информации о компании.');
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