<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\ClientController\ClientController;
use Illuminate\Http\Request;
use App\Http\Requests\PointRequest;

use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Classs;
use App\Student;
use App\DotXetDiem;
use App\SchoolYear;
use App\Point;
use App\MyPoint;
use App\Diem;

class MyPointController extends ClientController
{
    public function getList(Request $request)
    {
        $list = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
        ->where('id_class', $request->session()->get('account')->id_class)
        ->join('points', 'points.id_dot', '=' , 'dot_xet_diem.id_dot_xet')
        ->where('showP', 1)
        ->where('id_student', $request->session()->get('account')->id_student)
        ->get();
        $tmp = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
        ->where('id_class', $request->session()->get('account')->id_class)
        ->join('points', 'points.id_dot', '=' , 'dot_xet_diem.id_dot_xet')
        ->where('id_student', $request->session()->get('account')->id_student)
        ->groupBy('nam_hoc')
        ->orderBy('nam_hoc')
        ->select('nam_hoc')
        ->get();
        $dataChart = [];
        foreach ($tmp as $key => $value) {
            $data_detail['nam_hoc'] = $value->nam_hoc;
            $k = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
            ->where('id_class', $request->session()->get('account')->id_class)
            ->where('nam_hoc', $value->nam_hoc)
            ->join('points', 'points.id_dot', '=' , 'dot_xet_diem.id_dot_xet')
            ->where('id_student', $request->session()->get('account')->id_student)
            ->where('dot_xet_diem.hoc_ki', 1)
            ->select('points.total')
            ->get();
            $data_detail['ki_1'] = (count($k) > 0) ? $k->first()->total : -1; 
            $k = DotXetDiem::join('dot_xet_diem_rela_class', 'dot_xet_diem_rela_class.id_dot', '=', 'dot_xet_diem.id_dot_xet')
            ->where('id_class', $request->session()->get('account')->id_class)
            ->where('nam_hoc', $value->nam_hoc)
            ->join('points', 'points.id_dot', '=' , 'dot_xet_diem.id_dot_xet')
            ->where('id_student', $request->session()->get('account')->id_student)
            ->where('dot_xet_diem.hoc_ki', 2)
            ->select('points.total')
            ->get();
            $data_detail['ki_2'] = (count($k) > 0) ? $k->first()->total : -1;     
            $dataChart[] = $data_detail;   
        }
        
        return view('client.my_point.danh_sach_dot', ['list' => $list, 'dataChart' => $dataChart]);
    }

    public function getDanhGia(PointRequest $request, $id_dot)
    {
        $id_student =  $request->session()->get('account')->id_student;
        $dot = DotXetDiem::where('id_dot_xet', $id_dot)->get()->first();
        if (empty($dot)){
            return redirect()->route('danh_sach_dot')->with('myErrors', "Đợt xét điểm không tồn tại");
        }

        $student = Student::where('id_student', $id_student)->get()->first();
        if (empty($student)){
            return redirect()->route('getDot', ['id_dot' => $id_dot])->with('myErrors', "Sinh viên không tồn tại");
        }
        try {
            $client = new Client();
            $namhoc = explode('-', $dot->nam_hoc);
            $query = [
                'masv' => $id_student,
                'nambatdau' => (int) $namhoc[0],
                'namketthuc' => (int) $namhoc[1],
                'hocky' => $dot->hoc_ki
            ];
            $res = $client->request('GET', 'http://diemrenluyen.xyz/diem_sv', [ 
                'query' => $query,
            ]); 
            $content = (object) $res->getBody();
            $json = json_decode($content->getContents(), true);
            $point_study = $json;
            // Diem::where('id_student', $id_student)->where('id_dot_xet_diem', $id_dot)->update(["point"=>$point_study]);
        } catch (\Throwable $th) {
           $point_study = 0;
        }
        // $point_study = Diem::where('id_student', $id_student)->where('id_dot_xet_diem', $dot->id_dot_xet)->get();
        // if (empty($point_study)) $point_study = 0;
        // else $point_study = $point_study->first()->point;

        
        
        $my_point = MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        if ($my_point->confirm == 0){

            $arr_update = [
                "p1a" => 0,
                "p1b1" => 0,
                "p1b2" => 0,
                "p1c" => 0,
                "p1d" => 0,
                "p1dd" => convertPointToPoint($point_study),
                "p2a1" => 0,
                "p2a2" => 0,
                "p2b1" => 0,
                "p2b2" => 0,
                "p3a1" => 0,
                "p3a2" => 0,
                "p3b1" => 0,
                "p3c" => 0,
                "p4a1" => 0,
                "p4a2" => 0,
                "p4a3" => 0,
                "p4b" => 0,
                "p4c" => 0,
                "p5a" => 0,
                "p5b" => 0,
                "p5c" => 0,
                "p5d" => 0,
                "confirm" => 1,
            ];
            MyPoint::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->update($arr_update);
             updateTotalMyPoint($my_point->id_my_point);

        }
        $arr_update = [
            "p1dd" => convertPointToPoint($point_study),
        ];
        MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->update($arr_update);
         updateTotalMyPoint($my_point->id_my_point);
        Point::where('id_dot', $id_dot)
         ->where('id_student', $id_student)
         ->update($arr_update);
          updateTotalMyPoint($my_point->id_my_point);

        $my_point = Point::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();
        

        $my_temp_point = MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        return view('client.my_point.tu_danh_gia', ['my_point' => $my_point, 'my_temp_point' => $my_temp_point, 'dot' => $dot, 'point' => $point_study]);
    }

    function postDanhGia(PointRequest $request, $id_dot){

        $input = $request->all();
        $id_student =  $request->session()->get('account')->id_student;
        $arr_update = [
            "p1a" => $input['p1a'],
            "p1b1" => $input['p1b1'],
            "p1b2" => $input['p1b2'],
            "p1c" => $input['p1c'],
            "p1d" => $input['p1d'],
            "p2a1" => $input['p2a1'],
            "p2a2" => $input['p2a2'],
            "p2b1" => $input['p2b1'],
            "p2b2" => $input['p2b2'],
            "p3a1" => $input['p3a1'],
            "p3a2" => $input['p3a2'],
            "p3b1" => $input['p3b1'],
            "p3c" => $input['p3c'],
            "p4a1" => $input['p4a1'],
            "p4a2" => $input['p4a2'],
            "p4a3" => $input['p4a3'],
            "p4b" => $input['p4b'],
            "p4c" => $input['p4c'],
            "p5a" => $input['p5a'],
            "p5b" => $input['p5b'],
            "p5c" => $input['p5c'],
            "p5d" => $input['p5d'],
            "confirm" => 1,
        ];

        MyPoint::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->update($arr_update);
        $my_point = MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();
        updateTotalMyPoint($my_point->id_my_point);

        $res = [
            "code" => 200,
            "message" => "Finish",
            "data" => $input,
            
        ];

        return $res;

    }

    public function getPublicPoint(Request $request, $id_dot)
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
        foreach ($students as $key => $student) {
        
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
        // return $students;
        return view('client.my_point.list_sinh_vien_dot',["students" => $students, 'id_dot' => $id_dot, 'dot' => $dot]);
    }

}
