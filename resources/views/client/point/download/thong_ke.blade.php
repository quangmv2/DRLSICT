<!DOCTYPE html>
<html lang="vi">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-32x32.png') }}">
  <title>Bảng thống kê kết quả rèn luyện {{ $classs->id_class }} - Học kỳ: {{ hocKy($dot->hoc_ki) }} - Năm học: {{ $dot->nam_hoc }} </title>

  <!-- Custom fonts for this template-->
  
  
  
  <style>
    a{
        text-decoration: none!important;
    }
    body {
        font-family: DejaVu Sans;
    }
    table {
        border-collapse: collapse;
    }
    td{
        font-size: 14px;
    }
    th{
        font-size: 14px;
    }


    </style>

 
  
</head>
<body>

    <style>
        .mytable table {
            width: 100%;
            margin: auto;
            margin-bottom: 10px;
        }
        .mytable th, td {
            /* border: 1px solid black; */
            border-collapse: collapse;
            }
            th, td {
            padding: 5px;
            text-align: left;    
            }
        .mytable tr{
            width: 100%;
        }
    </style>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <table class="myTable" id="myTable" 
                style=" width: 100%; 
                        margin: auto;
                        margin-bottom: 20px;"
                >
                    <tr>
                        <td style="text-align: center; text-transform: uppercase; font-weight: bold">Đại học Đà Nẵng</td>
                        <td style="text-align: center; text-transform: uppercase; font-weight: bold">Cộng hòa xã hội chủ nghĩa Việt Nam</td>
                    </tr>
                    
                    <tr>
                        <td style="text-align: center; text-transform: uppercase; font-weight: bold">Khoa CNTT & Truyền thông</td>
                        <td style="text-align: center; text-transform: uppercase; font-weight: bold">Độc lập - Tự do - Hạnh phúc</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center"><h3><strong style="text-transform: uppercase;">BẢNG TỔNG HỢP KẾT QUẢ RÈN LUYỆN <br>HỌC KỲ: {{ hocKy($dot->hoc_ki) }} - NĂM HỌC: {{ $dot->nam_hoc }}</strong></h3></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center">(Lớp: {{ $classs->id_class }} - Khóa học: {{ explode('-', $classs->start_study)[0]."-".explode('-', $classs->end_study)[0] }})</td>
                    </tr>
                </table>
            </div>

        </div>
        <div class="row">

            <div class="col-sm-12 col-lg-12 col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><p
                                style="display: flex; justify-content: center; align-items: center; text-align: center"    
                            >STT</p></th>
                            <th style="width: 30%"><p
                                style="display: flex; justify-content: center; align-items: center; text-align: center"    
                            >Họ và tên sinh viên</p></th>
                            <th><p
                                style="justify-content: center; align-items: center; text-align: center"    
                            >Mã sinh viên</p></th>
                            <th><p
                                style="justify-content: center; align-items: center; text-align: center"    
                            >Điểm rèn luyện</p></th>
                            <th><p
                                style="justify-content: center; align-items: center; text-align: center"    
                            >Kết quả xét điểm rèn luyện</p></th>
                            <th><p
                                style="display: flex; justify-content: center; align-items: center; text-align: center"    
                            >Ghi chú</p></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $index => $student)
                            <tr>
                                <td style="width: 2%; text-align: center">{{$index + 1}}</td>
                                <td>{{$student->first_name." ".$student->last_name}}</td>
                                <td style="text-align: center">{{$student->id_student}}</td>
                                <td style="text-align: center">{{$student->total}}</td>
                                <td style="text-align: center">{{danhGia($student->total)}}</td>
                                <td style="text-align: center">{{$student->note}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p style="text-align: center; font-size: 15px; font-style: italic">(Tổng cộng danh sách có: {{$count}} sinh viên được đánh giá kết quả rèn luyện)</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">

            </div>
            <div class="col-sm-10">
                <p style="font-size: 15px">Trong đó:</p>
                <div class="col-sm-1">
                @php
                    if ($count == 0) $count = 1;
                @endphp
                </div>
                <div class="col-sm-11">
                    <table class="myTable" style=" width: 100%; 
                    margin: auto;
                    margin-bottom: 20px;">
                        <tr>
                            <td>Loại Xuất sắc có:</td>
                            <td>{{$result['xuat_sac']}}</td>
                            <td>sinh viên, chiếm tỷ lệ:</td>
                            <td>{{round(($result['xuat_sac']/$count)*100, 2)}}</td>
                            <td>(%);</td>
                        </tr>
                        <tr>
                            <td>Loại Giỏi có:</td>
                            <td>{{$result['gioi']}}</td>
                            <td>sinh viên, chiếm tỷ lệ:</td>
                            <td>{{round(($result['gioi']/$count)*100, 2)}}</td>
                            <td>(%);</td>
                        </tr>
                        <tr>
                            <td>Loại Khá có:</td>
                            <td>{{$result['kha']}}</td>
                            <td>sinh viên, chiếm tỷ lệ:</td>
                            <td>{{round(($result['kha']/$count)*100, 2)}}</td>
                            <td>(%);</td>
                        </tr>
                        <tr>
                            <td>Loại Trung bình có:</td>
                            <td>{{$result['trung_binh']}}</td>
                            <td>sinh viên, chiếm tỷ lệ:</td>
                            <td>{{round(($result['trung_binh']/$count)*100, 2)}}</td>
                            <td>(%);</td>
                        </tr>
                        <tr>
                            <td>Loại Yếu có:</td>
                            <td>{{$result['yeu']}}</td>
                            <td>sinh viên, chiếm tỷ lệ:</td>
                            <td>{{round(($result['yeu']/$count)*100, 2)}}</td>
                            <td>(%);</td>
                        </tr>
                        <tr>
                            <td>Loại Kém có:</td>
                            <td>{{$result['kem']}}</td>
                            <td>sinh viên, chiếm tỷ lệ:</td>
                            <td>{{round(($result['kem']/$count)*100, 2)}}</td>
                            <td>(%);</td>
                        </tr>

                    </table>
                </div>
            </div>
        </div>
        <style>
            .tableKy td th{
                text-align: center
            }
        </style>
        <div class="row">
            <div class="col-sm-12">

                <table class="myTable tableKy"
                style=" width: 100%; 
                        margin: auto;
                        margin-bottom: 20px;"
                >

                    <tr>
                        <td></td>
                        <td style="text-align: center" colspan="">Ngày .... tháng .... năm 20.....</td>
                    </tr>
                    <tr>
                        <th style="text-align: center">Giảng viên chủ nhiệm</th>
                        <th style="text-align: center">Lớp trưởng</th>
                    </tr>
                    <tr style="margin-bottom: 75px">
                        <td style="text-align: center">(ký, ghi rõ họ tên)</td>
                        <td style="text-align: center">(ký, ghi rõ họ tên)</td>
                    </tr>
                   
                    @php
                        for ($i=0; $i < 8; $i++) { 
                            echo "<tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>";
                        }
                    @endphp
                    <tr >
                        <td></td>
                        <td></td>
                        {{-- <td style="text-align: center; font-weight: bold"> {{$student->first_name." ".$student->last_name}} </td> --}}
                    </tr>
                </table>

            </div>
        </div>

    </div>
    <style>
        .borderless table {
            border-top-style: none;
            border-left-style: none;
            border-right-style: none;
            border-bottom-style: none;
        }
        .container-fluid {
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
        }

        .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
        }
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col,
        .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm,
        .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md,
        .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg,
        .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl,
        .col-xl-auto {
        position: relative;
        width: 100%;
        padding-right: 15px;
        padding-left: 15px;
        }
        .col {
        -ms-flex-preferred-size: 0;
        flex-basis: 0;
        -ms-flex-positive: 1;
        flex-grow: 1;
        max-width: 100%;
        }

        .col-auto {
        -ms-flex: 0 0 auto;
        flex: 0 0 auto;
        width: auto;
        max-width: 100%;
        }

        .col-1 {
        -ms-flex: 0 0 8.333333%;
        flex: 0 0 8.333333%;
        max-width: 8.333333%;
        }

        .col-2 {
        -ms-flex: 0 0 16.666667%;
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
        }

        .col-3 {
        -ms-flex: 0 0 25%;
        flex: 0 0 25%;
        max-width: 25%;
        }

        .col-4 {
        -ms-flex: 0 0 33.333333%;
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        }

        .col-5 {
        -ms-flex: 0 0 41.666667%;
        flex: 0 0 41.666667%;
        max-width: 41.666667%;
        }

        .col-6 {
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
        }

        .col-7 {
        -ms-flex: 0 0 58.333333%;
        flex: 0 0 58.333333%;
        max-width: 58.333333%;
        }

        .col-8 {
        -ms-flex: 0 0 66.666667%;
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        }

        .col-9 {
        -ms-flex: 0 0 75%;
        flex: 0 0 75%;
        max-width: 75%;
        }

        .col-10 {
        -ms-flex: 0 0 83.333333%;
        flex: 0 0 83.333333%;
        max-width: 83.333333%;
        }

        .col-11 {
        -ms-flex: 0 0 91.666667%;
        flex: 0 0 91.666667%;
        max-width: 91.666667%;
        }

        .col-12 {
        -ms-flex: 0 0 100%;
        flex: 0 0 100%;
        max-width: 100%;
        }

        .order-first {
        -ms-flex-order: -1;
        order: -1;
        }

        .order-last {
        -ms-flex-order: 13;
        order: 13;
        }

        .order-0 {
        -ms-flex-order: 0;
        order: 0;
        }

        .order-1 {
        -ms-flex-order: 1;
        order: 1;
        }

        .order-2 {
        -ms-flex-order: 2;
        order: 2;
        }

        .order-3 {
        -ms-flex-order: 3;
        order: 3;
        }

        .order-4 {
        -ms-flex-order: 4;
        order: 4;
        }

        .order-5 {
        -ms-flex-order: 5;
        order: 5;
        }

        .order-6 {
        -ms-flex-order: 6;
        order: 6;
        }

        .order-7 {
        -ms-flex-order: 7;
        order: 7;
        }

        .order-8 {
        -ms-flex-order: 8;
        order: 8;
        }

        .order-9 {
        -ms-flex-order: 9;
        order: 9;
        }

        .order-10 {
        -ms-flex-order: 10;
        order: 10;
        }

        .order-11 {
        -ms-flex-order: 11;
        order: 11;
        }

        .order-12 {
        -ms-flex-order: 12;
        order: 12;
        }

        .offset-1 {
        margin-left: 8.333333%;
        }

        .offset-2 {
        margin-left: 16.666667%;
        }

        .offset-3 {
        margin-left: 25%;
        }

        .offset-4 {
        margin-left: 33.333333%;
        }

        .offset-5 {
        margin-left: 41.666667%;
        }

        .offset-6 {
        margin-left: 50%;
        }

        .offset-7 {
        margin-left: 58.333333%;
        }

        .offset-8 {
        margin-left: 66.666667%;
        }

        .offset-9 {
        margin-left: 75%;
        }

        .offset-10 {
        margin-left: 83.333333%;
        }

        .offset-11 {
        margin-left: 91.666667%;
        }

        @media (min-width: 576px) {
        .col-sm {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -ms-flex-positive: 1;
            flex-grow: 1;
            max-width: 100%;
        }
        .col-sm-auto {
            -ms-flex: 0 0 auto;
            flex: 0 0 auto;
            width: auto;
            max-width: 100%;
        }
        .col-sm-1 {
            -ms-flex: 0 0 8.333333%;
            flex: 0 0 8.333333%;
            max-width: 8.333333%;
        }
        .col-sm-2 {
            -ms-flex: 0 0 16.666667%;
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
        }
        .col-sm-3 {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
        }
        .col-sm-4 {
            -ms-flex: 0 0 33.333333%;
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .col-sm-5 {
            -ms-flex: 0 0 41.666667%;
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }
        .col-sm-6 {
            -ms-flex: 0 0 50%;
            flex: 0 0 50%;
            max-width: 50%;
        }
        .col-sm-7 {
            -ms-flex: 0 0 58.333333%;
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }
        .col-sm-8 {
            -ms-flex: 0 0 66.666667%;
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .col-sm-9 {
            -ms-flex: 0 0 75%;
            flex: 0 0 75%;
            max-width: 75%;
        }
        .col-sm-10 {
            -ms-flex: 0 0 83.333333%;
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }
        .col-sm-11 {
            -ms-flex: 0 0 91.666667%;
            flex: 0 0 91.666667%;
            max-width: 91.666667%;
        }
        .col-sm-12 {
            -ms-flex: 0 0 100%;
            flex: 0 0 100%;
            max-width: 100%;
        }
        .order-sm-first {
            -ms-flex-order: -1;
            order: -1;
        }
        .order-sm-last {
            -ms-flex-order: 13;
            order: 13;
        }
        .order-sm-0 {
            -ms-flex-order: 0;
            order: 0;
        }
        .order-sm-1 {
            -ms-flex-order: 1;
            order: 1;
        }
        .order-sm-2 {
            -ms-flex-order: 2;
            order: 2;
        }
        .order-sm-3 {
            -ms-flex-order: 3;
            order: 3;
        }
        .order-sm-4 {
            -ms-flex-order: 4;
            order: 4;
        }
        .order-sm-5 {
            -ms-flex-order: 5;
            order: 5;
        }
        .order-sm-6 {
            -ms-flex-order: 6;
            order: 6;
        }
        .order-sm-7 {
            -ms-flex-order: 7;
            order: 7;
        }
        .order-sm-8 {
            -ms-flex-order: 8;
            order: 8;
        }
        .order-sm-9 {
            -ms-flex-order: 9;
            order: 9;
        }
        .order-sm-10 {
            -ms-flex-order: 10;
            order: 10;
        }
        .order-sm-11 {
            -ms-flex-order: 11;
            order: 11;
        }
        .order-sm-12 {
            -ms-flex-order: 12;
            order: 12;
        }
        .offset-sm-0 {
            margin-left: 0;
        }
        .offset-sm-1 {
            margin-left: 8.333333%;
        }
        .offset-sm-2 {
            margin-left: 16.666667%;
        }
        .offset-sm-3 {
            margin-left: 25%;
        }
        .offset-sm-4 {
            margin-left: 33.333333%;
        }
        .offset-sm-5 {
            margin-left: 41.666667%;
        }
        .offset-sm-6 {
            margin-left: 50%;
        }
        .offset-sm-7 {
            margin-left: 58.333333%;
        }
        .offset-sm-8 {
            margin-left: 66.666667%;
        }
        .offset-sm-9 {
            margin-left: 75%;
        }
        .offset-sm-10 {
            margin-left: 83.333333%;
        }
        .offset-sm-11 {
            margin-left: 91.666667%;
        }
        }
        .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        }

        .table th,
        .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        }

        .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        }

        .table tbody + tbody {
        border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
        padding: 0.3rem;
        }

        .table-bordered {
        border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
        border: 1px solid #dee2e6;
        }

        .table-bordered thead th,
        .table-bordered thead td {
        border-bottom-width: 2px;
        }

        .table-borderless th,
        .table-borderless td,
        .table-borderless thead th,
        .table-borderless tbody + tbody {
        border: 0;
        }

        .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
        color: #212529;
        background-color: rgba(0, 0, 0, 0.075);
        }

        .table-primary,
        .table-primary > th,
        .table-primary > td {
        background-color: #b8daff;
        }

        .table-primary th,
        .table-primary td,
        .table-primary thead th,
        .table-primary tbody + tbody {
        border-color: #7abaff;
        }

        .table-hover .table-primary:hover {
        background-color: #9fcdff;
        }

        .table-hover .table-primary:hover > td,
        .table-hover .table-primary:hover > th {
        background-color: #9fcdff;
        }

        .table-secondary,
        .table-secondary > th,
        .table-secondary > td {
        background-color: #d6d8db;
        }

        .table-secondary th,
        .table-secondary td,
        .table-secondary thead th,
        .table-secondary tbody + tbody {
        border-color: #b3b7bb;
        }

        .table-hover .table-secondary:hover {
        background-color: #c8cbcf;
        }

        .table-hover .table-secondary:hover > td,
        .table-hover .table-secondary:hover > th {
        background-color: #c8cbcf;
        }

        .table-success,
        .table-success > th,
        .table-success > td {
        background-color: #c3e6cb;
        }

        .table-success th,
        .table-success td,
        .table-success thead th,
        .table-success tbody + tbody {
        border-color: #8fd19e;
        }

        .table-hover .table-success:hover {
        background-color: #b1dfbb;
        }

        .table-hover .table-success:hover > td,
        .table-hover .table-success:hover > th {
        background-color: #b1dfbb;
        }

        .table-info,
        .table-info > th,
        .table-info > td {
        background-color: #bee5eb;
        }

        .table-info th,
        .table-info td,
        .table-info thead th,
        .table-info tbody + tbody {
        border-color: #86cfda;
        }

        .table-hover .table-info:hover {
        background-color: #abdde5;
        }

        .table-hover .table-info:hover > td,
        .table-hover .table-info:hover > th {
        background-color: #abdde5;
        }

        .table-warning,
        .table-warning > th,
        .table-warning > td {
        background-color: #ffeeba;
        }

        .table-warning th,
        .table-warning td,
        .table-warning thead th,
        .table-warning tbody + tbody {
        border-color: #ffdf7e;
        }

        .table-hover .table-warning:hover {
        background-color: #ffe8a1;
        }

        .table-hover .table-warning:hover > td,
        .table-hover .table-warning:hover > th {
        background-color: #ffe8a1;
        }

        .table-danger,
        .table-danger > th,
        .table-danger > td {
        background-color: #f5c6cb;
        }

        .table-danger th,
        .table-danger td,
        .table-danger thead th,
        .table-danger tbody + tbody {
        border-color: #ed969e;
        }

        .table-hover .table-danger:hover {
        background-color: #f1b0b7;
        }

        .table-hover .table-danger:hover > td,
        .table-hover .table-danger:hover > th {
        background-color: #f1b0b7;
        }

        .table-light,
        .table-light > th,
        .table-light > td {
        background-color: #fdfdfe;
        }

        .table-light th,
        .table-light td,
        .table-light thead th,
        .table-light tbody + tbody {
        border-color: #fbfcfc;
        }

        .table-hover .table-light:hover {
        background-color: #ececf6;
        }

        .table-hover .table-light:hover > td,
        .table-hover .table-light:hover > th {
        background-color: #ececf6;
        }

        .table-dark,
        .table-dark > th,
        .table-dark > td {
        background-color: #c6c8ca;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th,
        .table-dark tbody + tbody {
        border-color: #95999c;
        }

        .table-hover .table-dark:hover {
        background-color: #b9bbbe;
        }

        .table-hover .table-dark:hover > td,
        .table-hover .table-dark:hover > th {
        background-color: #b9bbbe;
        }

        .table-active,
        .table-active > th,
        .table-active > td {
        background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover {
        background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover > td,
        .table-hover .table-active:hover > th {
        background-color: rgba(0, 0, 0, 0.075);
        }

        .table .thead-dark th {
        color: #fff;
        background-color: #343a40;
        border-color: #454d55;
        }

        .table .thead-light th {
        color: #495057;
        background-color: #e9ecef;
        border-color: #dee2e6;
        }

        .table-dark {
        color: #fff;
        background-color: #343a40;
        }

        .table-dark th,
        .table-dark td,
        .table-dark thead th {
        border-color: #454d55;
        }

        .table-dark.table-bordered {
        border: 0;
        }

        .table-dark.table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(255, 255, 255, 0.05);
        }

        .table-dark.table-hover tbody tr:hover {
        color: #fff;
        background-color: rgba(255, 255, 255, 0.075);
        }

        @media (max-width: 575.98px) {
        .table-responsive-sm {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive-sm > .table-bordered {
            border: 0;
        }
        }

        @media (max-width: 767.98px) {
        .table-responsive-md {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive-md > .table-bordered {
            border: 0;
        }
        }

        @media (max-width: 991.98px) {
        .table-responsive-lg {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive-lg > .table-bordered {
            border: 0;
        }
        }

        @media (max-width: 1199.98px) {
        .table-responsive-xl {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive-xl > .table-bordered {
            border: 0;
        }
        }

        .table-responsive {
        display: block;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        }

        .table-responsive > .table-bordered {
        border: 0;
        }

    </style>
</body>
</html>
