@extends('client.index')
@section('content')

<div class="container-fluid" style="margin-left: 0px;">

    <div class="row">
    

        <div class="col-sm-12" >
            
            <h1>THÊM MỚI <small>Đợt xét điểm rèn luyện</small> </h1>

        </div>

    </div>

</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
                @if (count($errors)>0)
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $element)
                                {{ $element }} <br>
                            @endforeach
                        </div>
                @endif
                @if (session('myError'))
                        <div class="alert alert-danger">
                            {{ session('myError') }}
                        </div>
                @endif
                @if (session('notification'))
                        <div class="alert alert-success">
                            {{ session('notification') }}
                        </div>
                    @endif
        </div>
        <div class="col-sm-12">
            
                <form action="" method="POST" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <div class="form-group">

                        <label>Tên đợt</label>

                        <input type="text" name="name" value="" class="form-control" placeholder="Tên hoạt động" required>

                    </div>

                    <div class="form-group">

                        <label>Năm học</label>

                        <select name="year" id="" class="form-control">
                           @foreach ($arr as $value)
                               <option value="{{$value}}">{{$value}}</option>
                           @endforeach
                        </select>

                    </div>

                    <div class="form-group">

                        <label>Học kì</label>

                        <select name="semester" id="" class="form-control">
                            <option value="1">I</option>
                            <option value="2">II</option>
                        </select>

                    </div>

                    <div class="form-group">

                        <label>Ngày bắt đầu tính hoạt động</label>

                        <input type="date" name="begin" value="" class="form-control" required>

                    </div>

                    <div class="form-group">

                        <label>Ngày kết thúc tính hoạt động</label>

                        <input type="date" name="end" value="" class="form-control" required>

                    </div>
                    
                    <input type="submit" name="submit" class="btn btn-primary" value="THÊM MỚI">

                </form>
        </div>

    </div>
</div>
    
@endsection