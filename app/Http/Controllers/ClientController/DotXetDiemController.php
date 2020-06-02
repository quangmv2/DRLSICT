<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\ClientController\ClientController;
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use Validator;
use Carbon\Carbon;
use PDF;

use App\Classs;
use App\Student;
use App\DotXetDiem;
use App\SchoolYear;
use App\Point;
use App\MyPoint;

class DotXetDiemController extends ClientController
{
    public function danhSachDot(Request $request)
    {
        if (empty($request->input('page'))){
            $page = 1;
        } else{
            $page = $request->input('page');
        }

        $class = $request->session()->get('account')->id_class;

        $list = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
        ->where('id_class', $class)->where('showP', 1)->orderby('nam_hoc')->select('dot_xet_diem.*')->orderby('hoc_ki')->get();
        foreach ($list as $index  => $value){
            $list[$index]['xuat_sac'] = Point::join('students', 'students.id_student', '=', 'points.id_student')->where('id_class', $class)->where('id_dot', $value->id_dot_xet)->where('total', '>=', 90)->count('total');
            $list[$index]['gioi'] = Point::join('students', 'students.id_student', '=', 'points.id_student')->where('id_class', $class)->where('id_dot', $value->id_dot_xet)->where('total', '>=', 80)->where('total','<', 90)->count('total');
            $list[$index]['kha'] = Point::join('students', 'students.id_student', '=', 'points.id_student')->where('id_class', $class)->where('id_dot', $value->id_dot_xet)->where('total', '>=', 65)->where('total','<', 80)->count('total');
            $list[$index]['trung_binh'] = Point::join('students', 'students.id_student', '=', 'points.id_student')->where('id_class', $class)->where('id_dot', $value->id_dot_xet)->where('total', '>=', 50)->where('total','<', 65)->count('total');
            $list[$index]['yeu'] = Point::join('students', 'students.id_student', '=', 'points.id_student')->where('id_class', $class)->where('id_dot', $value->id_dot_xet)->where('total', '>=', 35)->where('total','<', 50)->count('total');
            $list[$index]['kem'] = Point::join('students', 'students.id_student', '=', 'points.id_student')->where('id_class', $class)->where('id_dot', $value->id_dot_xet)->where('confirm', 1)->where('total','<', 35)->count('total');
        }
        if ($request->input('type') == 'ajax') {
            return view('client.point.ajax.danh_sach_dot', ['list' => $list]);
        } else {
            return view('client.point.danh_sach_dot', ['list' => $list]);
        }
    }

    public function danhSachDotChart(Request $request)
    {
        $admin = $request->input('admin');
        if($admin == 'true'){
            $id_class = $request->input('lop');
            echo 'admin';
            die();
        } else{
            $id_class = $request->session()->get('account')->id_class;
        }

        $nam_hoc = $request->input('nam_hoc');
        $hoc_ki = $request->input('hoc_ki');
        $list = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
        ->where('id_class', $id_class)
        ->where('nam_hoc', $nam_hoc)
        ->where('hoc_ki', $hoc_ki)
        ->get()->first();
        if (empty($list)) return [];
        $count = Point::where('id_dot', $list->id_dot_xet)->count('total');
        $list['xuat_sac'] = Point::where('id_dot', $list->id_dot_xet)->where('total', '>=', 90)->count('total');
        $list['gioi'] = Point::where('id_dot', $list->id_dot_xet)->where('total', '>=', 80)->where('total','<', 90)->count('total');
        $list['kha'] = Point::where('id_dot', $list->id_dot_xet)->where('total', '>=', 65)->where('total','<', 80)->count('total');
        $list['trung_binh'] = Point::where('id_dot', $list->id_dot_xet)->where('total', '>=', 50)->where('total','<', 65)->count('total');
        $list['yeu'] = Point::where('id_dot', $list->id_dot_xet)->where('total', '>=', 35)->where('total','<', 50)->count('total');
        $list['kem'] = Point::where('id_dot', $list->id_dot_xet)->where('confirm', 1)->where('total','<', 35)->count('total');

        $data = [];
        $data_detail['name'] = "Xuất sắc";
        $data_detail['y'] = $list['xuat_sac'] == 0 ? 0 : ($list['xuat_sac']/$count)*100;
        $data[] = $data_detail;

        $data_detail['name'] = "Giỏi";
        $data_detail['y'] = $list['gioi'] == 0 ? 0 : ($list['gioi']/$count)*100;
        $data[] = $data_detail;

        $data_detail['name'] = "Khá";
        $data_detail['y'] = $list['kha'] == 0 ? 0 : ($list['kha']/$count)*100;
        $data[] = $data_detail;

        $data_detail['name'] = "Trung bình";
        $data_detail['y'] = $list['trung_binh'] == 0 ? 0 : ($list['trung_binh']/$count)*100;
        $data[] = $data_detail;

        $data_detail['name'] = "Yếu";
        $data_detail['y'] = $list['yeu'] == 0 ? 0 : ($list['yeu']/$count)*100;
        $data[] = $data_detail;

        $data_detail['name'] = "Kém";
        $data_detail['y'] = $list['kem'] == 0 ? 0 : ($list['kem']/$count)*100;
        $data[] = $data_detail;

        return json_encode($data);

    }

    public function danhSachDotChartPhoDiem(Request $request)
    {
        $admin = $request->input('admin');
        if($admin == 'true'){
            $id_class = $request->input('lop');
        } else{
            $id_class = $request->session()->get('account')->id_class;
        }
        $nam_hoc = $request->input('nam_hoc');
        $hoc_ki = $request->input('hoc_ki');
        $list = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
        ->where('id_class', $id_class)
        ->where('nam_hoc', $nam_hoc)
        ->where('hoc_ki', $hoc_ki)
        ->get()->first();
        $points = Point::where('id_dot', $list->id_dot_xet)->where('confirm', 1)->select('total')->get();
        $data = [];
        for ($i=0; $i <= 100; $i++) { 
            $k=0;
            $data_detail = [];
            foreach ($points as $key => $point) {
                if ($i == $point->total) $k++;
            }
            if ($k==0) continue;
            $data_detail[] = "".$i;
            $data_detail[] = $k;
            $data[] = $data_detail;
        }

        return json_encode([
            'data' => $data,
            'id_dot' => $list->id_dot_xet,
        ]);

    }

    public function getDot(Request $request, $id_dot)
    {

        if ($id_dot == -1) {
            $dot = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
            ->where('id_class', $request->session()->get('account')->id_class)
            ->where('nam_hoc', $request->input('nam_hoc'))
            ->where('hoc_ki', $request->input('hoc_ki'))
            ->get()->first();
            if (empty($dot)) return abort('404');
            return redirect()->route('getDot', ['id_dot'=>$dot->id_dot_xet]);
        }

        $students = Point::where('id_dot', $id_dot)
        ->join('students', 'students.id_student', '=', 'points.id_student')
        ->join('profiles', 'profiles.id_profile', '=', 'students.id_profile')
        ->where('students.id_class', $request->session()->get('account')->id_class)
        ->orderby('students.id_student')
        ->select('*')
        ->get();
        $dot = DotXetDiem::where('id_dot_xet', $id_dot)->get()->first();
        
        if (empty($dot)) return abort('404');
        // return $students->tojson();
        foreach ($students as $key => $student) {
            // try {
            //     $client = new Client();
            //     $namhoc = explode('-', $dot->nam_hoc);
            //     $query = [
            //         'masv' => $student->id_student,
            //         'nambatdau' => (int) $namhoc[0],
            //         'namketthuc' => (int) $namhoc[1],
            //         'hocky' => $dot->hoc_ki
            //     ];
            //     $res = $client->request('GET', 'http://diemrenluyen.xyz/diem_sv', [ 
            //         'query' => $query,
            //     ]); 
            //     $content = (object) $res->getBody();
            //     $json = json_decode($content->getContents(), true);
            //     $point_study = $json;
            //     $student["point"] = $point_study;
            //     // return $point_study;
            //     // Diem::where('id_student', $student->id_student)->where('id_dot_xet_diem', $id_dot)->update(["point"=>$point_study]);
            // } catch (\Throwable $th) {
            // //    $student["point"] = Diem::where('id_student', $student->id_student)->where('id_dot_xet_diem', $id_dot)->get()->first();
            //     $student["point"] = 0;
            // }
            $nam_hoc = explode('-', $dot->nam_hoc);
            $student["point"] = [
                'masv' => $student->id_student,
                'nambatdau' => $nam_hoc[0],
                'namketthuc' => $nam_hoc[1],
                'hocky' => $dot->hoc_ki
            ];
            $student["my_point"] = MyPoint::where('id_dot', $student->id_dot)
            ->where('id_student', $student->id_student)
            ->get()->first()->total;
        }
        return view('client.point.list_sinh_vien_dot',["students" => $students, 'id_dot' => $id_dot, 'dot' => $dot]);
    }

    public function delete(Request $request, $id_dot){

        $dot = DotXetDiem::find($id_dot);
        if (empty($dot)) return back();
        DotXetDiem::find($id_dot)->delete();
        return back();

    }

    public function changeStatus(Request $request, $id_dot)
    {

        $point = Point::where('id_dot', $id_dot)
        ->where('id_student', $request->input('id_student'))
        ->get()->first();
        if (empty($point)) {
            return [
                'ok' => 0,
            ];
        }
        $k = $point->status;
        if ($k==1){
            Point::where('id_dot', $id_dot)
            ->where('id_student', $request->input('id_student'))->update(['status'=>0, 'note'=>'Nghỉ học']);
            return [
                'ok' => 1,
                'message' => 'Nghỉ học',
                'note' => 'Nghỉ học',
                'class' => 'btn btn-danger',
            ];
        }
        Point::where('id_dot', $id_dot)
            ->where('id_student', $request->input('id_student'))->update(['status'=>1, 'note'=>'']);
            return [
                'ok' => 1,
                'message' => 'Đang học',
                'note' => '',
                'class' => 'btn btn-success',
            ];
        return $request->all();
    }

    public function downloadDotPDF(Request $request, $id_dot)
    {
        $id_class = $request->session()->get('account')->id_class;
        $dot = DotXetDiem::where('id_dot_xet', $id_dot)->get()->first();
        $students = Point::where('id_dot', $id_dot)
        ->join('students', 'students.id_student', '=', 'points.id_student')
        ->join('profiles', 'profiles.id_profile', '=', 'students.id_profile')
        ->where('students.id_class', $id_class)
        // ->where('points.status', '>', 0)
        ->orderby('students.id_student')
        ->select('students.*', 'points.total', 'points.note', 'profiles.first_name', 'profiles.last_name')
        ->get();
        // return $students;
        $list['xuat_sac'] = Point::where('id_dot', $id_dot)->where('total', '>=', 90)->where('status', '>', 0)->count('total');
        $list['gioi'] = Point::where('id_dot', $id_dot)->where('total', '>=', 80)->where('total','<', 90)->where('status', '>', 0)->count('total');
        $list['kha'] = Point::where('id_dot', $id_dot)->where('total', '>=', 65)->where('total','<', 80)->where('status', '>', 0)->count('total');
        $list['trung_binh'] = Point::where('id_dot', $id_dot)->where('total', '>=', 50)->where('total','<', 65)->where('status', '>', 0)->count('total');
        $list['yeu'] = Point::where('id_dot', $id_dot)->where('total', '>=', 35)->where('total','<', 50)->where('status', '>', 0)->count('total');
        $list['kem'] = Point::where('id_dot', $id_dot)->where('confirm', 1)->where('total','<', 35)->where('status', '>', 0)->count('total');
        // return $list["kem"]
        $count = Point::where('id_dot', $id_dot)->where('confirm', 1)->where('status', '>', 0)->count('total');
        // return view('client.point.download.thong_ke', 
        // [
        //     'dot' => $dot,
        //     'students' => $students,
        //     'count' => $count,
        //     'result' => $list,
        // ]);
        $class = Classs::where('id_class', $id_class)->get()->first();
        $pdf = PDF::loadView('client.point.download.thong_ke', 
        [
            'classs' => $class,
            'dot' => $dot,
            'students' => $students,
            'count' => $count,
            'result' => $list,
        ])->setPaper('a4', 'portrait');
        // $pdf->save(storage_path().'_filename.pdf');ss
        $type = $request->input('type');
        if ($type == 'read') return $pdf->stream($dot->id_class."_".$dot->nam_hoc."_".$dot->hoc_ki.".pdf");
        return $pdf->stream($dot->id_class."_".$dot->nam_hoc."_".$dot->hoc_ki.".pdf");
    }

    public function getNote(Request $request, $id_dot, $id_student)
    {
        $note = $request->input('note');
        Point::where('id_dot', $id_dot)->where('id_student', $id_student)->update(['note' => $note]);
        return $note;
    }

}
