@extends('client.index')
@section('title')Học kì {{$dot->hoc_ki}} Năm học {{$dot->nam_hoc}} @endsection
@section('content')
<div class="container-fluid" style="margin-left: 0px;">

    <div class="row">


        <div class="col-sm-12" >

            <h1>DANH SÁCH <small>Sinh Viên Học Kỳ {{ $dot->hoc_ki==1?"I":"II" }} Năm Học {{ $dot->nam_hoc }}</small> </h1>

        </div>

    </div>

</div>

<script>
     function getDTB(id, masv, nambatdau, namketthuc, hocky) {
        setTimeout(() => {
            $.ajax({
                url: 'http://diemrenluyen.xyz/diem_sv',
                data: {
                    masv, 
                    nambatdau,
                    namketthuc,
                    hocky
                },
                success: (data) => {
                    if (isNaN(parseFloat(data))) {
                        let rd = Math.random(1000);
                        setTimeout(() => {
                            getDTB(id, masv, nambatdau, namketthuc, hocky);
                        }, rd + 10000);
                    }
                    else document.getElementById(id).innerHTML = data
                }
            });
        }, Math.random(10000) + 5000);
     }
</script>

<div class="container-fluid">
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
                        <td>Mã SV</td>
                        <td>Họ và tên</td>
                        <td>Trạng thái</td>
                        <td>Điểm<br/>(Tự đánh giá/Lớp đánh giá)</td>
                        <td>Điểm TB</td>
                        <td>Xếp loại<br/>(Tự đánh giá/Lớp đánh giá)</td>
                        <td>Ghi chú</td>
                        {{-- <td>Tình trạng</td> --}}
                    </tr>
                </thead>

                <tbody id="dataPage">
                    @foreach ($students as $student)
                        <tr>
                            <td> {{ $student->id_student }} </td>
                            <td style="text-align: left; width: 20%"><a href="">{{ $student->first_name . " " . $student->last_name }}</a></td>
                            <td> {{ $student->confirm == 0 ? "Chưa đánh giá" : "Đã đánh giá" }} </td>
                            <td style="font-weight: bold">{{ $student["my_point"] }}/{{ $student->total }} </td>
                            <td id="DTB{{$student->id_student}}">
                                <div class="spinner-border text-warning" role="status">
                                    <span class="sr-only">Đang tải...</span>
                                  </div>
                            </td>
                            <td style="font-weight: bold"> {{ danhGia($student["my_point"]) }}/{{ danhGia($student->total) }} </td>
                            <td>{{$student->note}}</td>
                            {{-- <td><button type="button" class="
                                @if ($student->status == 1)
                                    btn btn-success
                                @else
                                    btn btn-danger
                                @endif    
                            " disabled
                            >
                                @if ($student->status == 1)
                                    Đang học
                                @else
                                    Nghỉ học
                                @endif
                            
                            </button></td> --}}
                        </tr>
                        <script>
                            getDTB('DTB{{ $student["point"]["masv"] }}', '{{ $student["point"]["masv"] }}', '{{ $student["point"]["nambatdau"] }}', '{{ $student["point"]["namketthuc"] }}', '{{ $student["point"]["hocky"] }}') 
                         </script>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('input').keyup(function (e) { 
            if (e.which == 13){
                e.preventDefault();
            }
            var id = this.id;
            $.ajax({
                type: "GET",
                url: '{{ route('getDotNote', ['id_dot'=> $id_dot, 'id_student' => '']) }}/'+id,
                data: {
                    note : this.value
                },
                success: function (response) {
                    console.log(response)
                }
            });
        });
    });

    function tinhTrang(button) { 
        var id = $(button).data('student');
        console.log(id)
        var url = '{{ route('changeStatus', ['id_dot'=>$id_dot]) }}?id_student='+id

        axios({
            url: url,
            method: "GET",
            data: {},
        })
        .then((response)=>{
            console.log(response)
            if (response.data.ok == 0) return;
            var data = response.data
            $(button).html(data.message)
            $(button).removeClass().addClass(data.class)
            $('#'+id).val(data.note) 
        })
        .catch((err)=>{
            console.log(err.response)
        })
        
    }
</script>

@endsection
