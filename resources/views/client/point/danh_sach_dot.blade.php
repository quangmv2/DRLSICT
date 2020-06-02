@extends('client.index')
@section('title')Danh sách đợt xét @endsection
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
            
            <h1><p style="display: inline" id="ds">DANH SÁCH</p> <small>Đợt xét điểm</small> </h1>
            <button type="button" class="btn btn-success" id="btnDisplay" style="display: inline; float: right; margin-bottom: 10px;">Thống kê</button>

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
            @foreach ($list as $item)
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header" style="color: black"><h4><a href="{{ route('getDot', ['id_dot'=>$item->id_dot_xet]) }}">Học kỳ {{$item->hoc_ki}} năm học {{$item->nam_hoc}}.</a></h4> </div>
                    <div class="card-body">
                        <h5 class="card-title">Xuất sắc: {{ $item->xuat_sac }}. Giỏi: {{ $item->gioi }}. Khá: {{ $item->kha }}. Trung bình: {{ $item->trung_binh }}. Yếu: {{ $item->yeu }}. Kém: {{ $item->kem }}.</h5>
                        <div style="float: right;">
                            <a href="{{ route('downDot', ['id_dot'=> $item->id_dot_xet]) }}"><i class="fas fa-cloud-download-alt" style="color: red"></i></a>
                            <a href=""><i class="fa fa-lock" aria-hidden="true" style="color: red"></i></a>
                            <a href="{{ route('downloadAllPointPDF', ['id_dot' => $item->id_dot_xet]) }}" target="_blank"><i class="fas fa-edit" style="color: red"></i></a>
                            {{-- <a href="{{ route('get_xoa_dot', ['id_dot'=>$item->id_dot_xet]) }}"><i class="fas fa-trash" style="color: red"></i></a> --}}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<div class="container-fluid" id="myChart" style="display: none">
    <div class="row">
        <div class="col-sm-12" id="myChart1"></div>
    </div>
    <div class="row">
        <div class="col-sm-6" id="myChart2"></div>
        <div class="col-sm-6" id="myChart3"></div>
    </div>
</div>
<figure class="highcharts-figure">
    <table id="datatable" style="display: none">
        <thead>
            <tr>
                <th></th>
                <th>Xuất sắc</th>
                <th>Giỏi</th>
                <th>Khá</th>
                <th>Trung bình</th>
                <th>Yếu</th>
                <th>Kém</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $value)
                <tr>
                    <th>Học kì {{ $value->hoc_ki }} - {{ $value->nam_hoc }}</th>
                    <td>{{ $value['xuat_sac'] }}</td>
                    <td>{{ $value['gioi'] }}</td>
                    <td>{{ $value['kha'] }}</td>
                    <td>{{ $value['trung_binh'] }}</td>
                    <td>{{ $value['yeu'] }}</td>
                    <td>{{ $value['kem'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</figure>

{{-- <script>
    callServer(1)
</script> --}}
<script>
    $(document).ready(function () {
        $('#btnDisplay').on('click', ()=>{
            console.log($('#myContent').css('display'))
            if ($('#myContent').css('display') === "none"){
                $('#myContent').show()
                $('#myChart').hide()
                $('#btnDisplay').html('Thống kê')
                $('#ds').html('DANH SÁCH')
            } else{
                $('#myContent').hide()
                $('#myChart').show()
                $('#btnDisplay').html('Bảng')
                $('#ds').html('BIỂU ĐỒ')
                Highcharts.chart('myChart1', {
                    data: {
                        table: 'datatable'
                    },
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Thống kê điểm rèn luyện tổng quát của lớp theo từng đợt'
                    },
                    yAxis: {
                        allowDecimals: false,
                        title: {
                            text: 'Số lượng sinh viên'
                        }
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>'
                                + this.point.name
                                + '<br/>Số lượng: ' + this.point.y 
                        }
                    },
                    plotOptions: {
                        series: {
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function(e) {
                                        callBackServer(this.name)
                                    }
                                }
                            },
                        }
                    },
                });
            }
        })
    });
    function callBackServer(context) {
        console.log(context)
        var k = context.split(" ")
        var sumary = k[2]
        var year = k[4]
        
        axios({
            url: window.location.href + "/chart?nam_hoc=" + year + "&hoc_ki=" + sumary,
            method: "GET",
            data: {}
        })
        .then((response)=>{
            console.log(response.data)
            fillPieChart(response.data, 'myChart2', "Thống kê điểm rèn luyện học kì " + sumary + " năm học " + year, '{{ route('getDot', ['id_dot'=>-1]) }}?' + 'nam_hoc=' + year + '&hoc_ki='+ sumary, 'Chi tiết')
            $("html, body").animate({ scrollTop: $(document).height() }, 'slow');
        })
        .catch((err)=>{
            ajaxSuccess()
        })

        axios({
            url: window.location.href + "/chartPhoDiem?nam_hoc=" + year + "&hoc_ki=" + sumary,
            method: "GET",
            data: {}
        })
        .then((response)=>{
            console.log(response.data)
            phoDiem(response.data.data, 'myChart3', "Phổ điểm rèn luyện học kì " + sumary + " năm học " + year, 
            '{{ route('getDot', ['id_dot'=>'']) }}/'+response.data.id_dot, "Học kì " + sumary + " Năm học " + year)
            $("html, body").animate({ scrollTop: $(document).height() }, 'slow');
        })
        .catch((err)=>{
            ajaxSuccess()
        })
    }
    function phoDiem(data, myLocaiton, title, link, name_link) {
        Highcharts.chart(myLocaiton, {
            chart: {
                type: 'column'
            },
            title: {
                text: title
            },
            subtitle: {
                text: 'Nguồn: <a href="'+ link +'">'+ name_link +'</a>'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Sinh viên'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                pointFormat: 'Số lượng: <b>{point.y} sinh viên</b>'
            },
            series: [{
                name: 'Population',
                data: data,
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            }]
        });
    }
</script>
@endsection