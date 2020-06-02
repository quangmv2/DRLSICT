@extends('client.index')
@section('title')Điểm rèn luyện của tôi @endsection
@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="{{ asset('assets/js/chart.js') }}"></script>
@endsection
@section('content')
<div class="container-fluid" style="margin-left: 0px;">

    <div class="row">


        <div class="col-sm-12" >

            <h1>DANH SÁCH <small>Đợt xét điểm</small> </h1>
            <button type="button" class="btn btn-success" id="btnDisplay" style="display: inline; float: right; margin-bottom: 10px;">Biểu đồ</button>

        </div>

    </div>

</div>

<div class="container-fluid" id="myContent">
    <div class="row">
        <style>
            td{
                text-align: center
            }
        </style>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12" id="dataPage">
            <table class="table table-striped table-bordered table-hover" style="width: 100%">

                <thead>
                    <tr>
                        <td>TT</td>
                        <td>Tên đợt</td>
                        <td>Trạng thái</td>
                        <td>Điểm</td>
                        <td>Xếp loại</td>
                        <td>Tự đánh giá</td>
                        <td>Tải về</td>
                        <td>Cả lớp</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $index => $item)
                        <tr>
                            <td> {{ $index + 1 }} </td>
                            <td> <a href="{{ route('getMyDot', ['id_dot'=> $item->id_dot_xet]) }}">Học kì {{ $item->hoc_ki }} năm học {{ $item->nam_hoc }} </a> </td>
                            <td> {{ $item->confirm == 0 ? "Chưa đánh giá" : "Đã đánh giá" }} </td>
                            <td> {{ $item->total }} </td>
                            <td> {{ danhGia($item->total) }} </td>
                            <td> <a href="{{ route('getMyDot', ['id_dot'=> $item->id_dot_xet]) }}"><i class="fas fa-notes-medical"></i></a> </td>
                            <td><a href="{{ route('downloadPointPDF', ['id_student' => session('account')->id_student, 'id_dot' => $item->id_dot_xet]) }}"><i class="fas fa-cloud-download-alt"></i></a>  </td>
                            <td><a href="{{ route('public-diem', ['id_dot' => $item->id_dot_xet]) }}">Xem</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12" id="myChart"></div>
    </div>
</div>
<figure class="highcharts-figure">
    <table id="datatable" style="display: none">
        <thead>
            <tr>
                <th></th>
                <th>Học kì I</th>
                <th>Học kì II</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataChart as $value)
                <tr>
                    <th>{{ $value['nam_hoc'] }}</th>
                    <td>{{ $value['ki_1'] > -1 ? $value['ki_1']: ''}}</td>
                    <td>{{ $value['ki_2'] > -1 ? $value['ki_2']: ''}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</figure>

<script>
    $(document).ready(function () {
        $('#btnDisplay').on('click', ()=>{
            console.log($('#myContent').css('display'))
            if ($('#myContent').css('display') === "none"){
                $('#myContent').show()
                $('#myChart').hide()
                $('#btnDisplay').html('Biểu đồ')
            } else{
                $('#myContent').hide()
                $('#myChart').show()
                $('#btnDisplay').html('Bảng')
                showChart()
            }
        })
    });
    function showChart() {
        Highcharts.chart('myChart', {
            data: {
                table: 'datatable',
            },
            chart: {
                type: 'column',
            },
            title: {
                text: 'Thống kê điểm rèn luyện của bạn'
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Điểm'
                }
            },
            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/><b>'
                        + this.point.name.toLowerCase() 
                        + '</b><br/>Điểm: ' + this.point.y 
                        + '<br/>Xếp loại: ' + danhGia(this.point.y);
                }
            },
            plotOptions: {
                series: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function(e) {
                            }
                        }
                    },
                }
            },
        });
    }
    
</script>

@endsection
