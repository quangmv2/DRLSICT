<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\ClientController\ClientController;
use Illuminate\Http\Request;
use App\Http\Requests\PointRequest;

use GuzzleHttp\Client;
use Carbon\Carbon;
use PDF;

use App\Classs;
use App\Student;
use App\DotXetDiem;
use App\SchoolYear;
use App\Point;
use App\MyPoint;
use App\Attendance;

class PointController extends ClientController
{

    function getDanhGia(Request $request, $id_dot, $id_detail,  $name, $id_student){
        if ($request->input('type') == 'dismis') {
            $arr_update = [
                "p1a" => 0,
                "p1b1" => 0,
                "p1b2" => 0,
                "p1c" => 0,
                "p1d" => 0,
                "p1dd" => 0,
                "p2a1" => 0,
                "p2a2" => 0,
                "p2b1" => 0,
                "p2b2" => 0,
                "p2b3" => 0,
                "p3a1" => 0,
                "p3a2" => 0,
                "p3b1" => 0,
                "p3b2" => 0,
                "p3b3" => 0,
                "p3c" => 0,
                "p4a1" => 0,
                "p4a2" => 0,
                "p4a3" => 0,
                "p4b" => 0,
                "p4c" => 0,
                "confirm" => 0,
                "total" => 0,
                "note" => "Nghỉ học",
            ];
            $id = $request->input('id');
            Point::where('id_point', $id)->update($arr_update);
            return \redirect()->route('getDanhGia', ['id_dot' => $id_dot, 'id_detail'=> $id_detail, 'name' => $name, 'id_student' => $id_student]);
        }
        $dot = DotXetDiem::where('id_dot_xet', $id_dot)->get()->first();
        if (empty($dot)){
            return redirect()->route('danh_sach_dot')->with('myErrors', "Đợt xét điểm không tồn tại");
        }

        $student = Student::where('id_student', $id_student)->get()->first();
        if (empty($student)){
            return redirect()->route('getDot', ['id_dot' => $id_dot])->with('myErrors', "Sinh viên không tồn tại");
        }

        $client = new Client();
        $res = $client->request('GET', 'http://diemrenluyen.xyz/point/'.$id_student);
        // $res = $client->request('GET', 'http://localhost:3000/api/point/'.$id_student);
        $content = (object) $res->getBody();
        $json = json_decode($content->getContents(), true);
        
        $point_study = $json['point'];

        $my_point = Point::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        $AR = Attendance::where('id_student', $id_student)
        ->join('action', 'action.id_action', '=', 'attendance.id_action')
        ->where('time', '>=', $dot->ngay_bat_dau)
        ->where('time', '<=', $dot->ngay_ket_thuc)
        ->select('attendance.*')
        ->get();
        $att = 0;
        foreach ($AR as $key => $value) {
            if ($value->confirm == 1) $att++;
        }
        $p4b = 0;
        switch ($att) {
            case '0':
                $p4b = 8;
                break;
            case '1':
                $p4b = 4;
                break;
        }

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
                "p2b3" => 0,
                "p3a1" => 0,
                "p3a2" => 0,
                "p3b1" => 0,
                "p3b2" => 0,
                "p3b3" => 0,
                "p3c" => 0,
                "p4a1" => 0,
                "p4a2" => 0,
                "p4a3" => 0,
                "p4b" => $p4b,
                "p4c" => 0,
                "confirm" => 1,
            ];
            Point::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->update($arr_update);
            updateTotal($my_point->id_point);
            MyPoint::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->update(['p1dd' => convertPointToPoint($point_study)]);
            updateTotalMyPoint(MyPoint::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->get()->first()->id_my_point);
        }


        $my_point = Point::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        $my_temp_point = MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        $points = Point::where('id_dot', $id_dot)
            ->join('students', 'students.id_student', '=', 'points.id_student')
            ->join('profiles', 'profiles.id_profile', '=', 'students.id_profile')
            ->select('*')
            ->orderby('students.id_student')->get();

        $vt = 0;
        foreach ($points as $index => $value) {
            if ($value->id_student == $id_student){
                $vt = $index;
                break;
            }
        }

        // return $points;

        if (empty($my_point) || empty($my_temp_point) || empty($points)) return abort(404);
        return view('client.point.danh_gia', [
            'my_point' => $my_point,
            'my_temp_point' => $my_temp_point,
            'students' => $points,
            'index' => $vt,
        ]);

    }

    function postDanhGia(PointRequest $request, $id_dot, $id_detail, $name, $id_student){

        $input = $request->all();

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
            "p2b3" => $input['p2b3'],
            "p3a1" => $input['p3a1'],
            "p3a2" => $input['p3a2'],
            "p3b1" => $input['p3b1'],
            "p3b2" => $input['p3b2'],
            "p3b3" => $input['p3b3'],
            "p3c" => $input['p3c'],
            "p4a1" => $input['p4a1'],
            "p4a2" => $input['p4a2'],
            "p4a3" => $input['p4a3'],
            "p4b" => $input['p4b'],
            "p4c" => $input['p4c'],
            "confirm" => 1,
        ];

        Point::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->update($arr_update);
        updateTotal($id_detail);

        $res = [
            "code" => 200,
            "message" => "Finish",
            "data" => $input,
            "id_detail" => $id_detail,
        ];

        return $res;

    }

    public function downloadPointPDF(Request $request, $id_student, $id_dot)
    {
        die();
        // PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $my_point = Point::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        $my_temp_point = MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

        $student = Student::where('id_student', $id_student)
        ->join('profiles', 'profiles.id_profile', '=', 'students.id_profile')
        ->get()->first();

        $dot = DotXetDiem::find($id_dot);

        // return view('client.point.download.danh_gia',
        // [
        //     'my_point' => $my_point,
        //     'my_temp_point' => $my_temp_point,
        //     'student' => $student,
        //     'dot' => $dot,
        // ]);

        $pdf = PDF::loadView('client.point.download.danh_gia',
        [
            'my_point' => $my_point,
            'my_temp_point' => $my_temp_point,
            'student' => $student,
            'dot' => $dot,
        ])->setPaper('a4');
        $type = $request->input('type');
        if ($type == 'read') {
            return $pdf->stream("Phiếu đánh giá kết quả rèn luyện sinh viên ".$student->first_name." ".$student->last_name." - Học kỳ: ".hocKy($dot->hoc_ki)." - Năm học: ". $dot->nam_hoc.'.pdf');
        } else if ($type == 'html') {
            return view('client.point.download.danh_gia',
                    [
                        'my_point' => $my_point,
                        'my_temp_point' => $my_temp_point,
                        'student' => $student,
                        'dot' => $dot,
                    ]);
        }
        return $pdf->download("Phiếu đánh giá kết quả rèn luyện sinh viên ".$student->first_name." ".$student->last_name." - Học kỳ: ".hocKy($dot->hoc_ki)." - Năm học: ". $dot->nam_hoc.'.pdf');

    }

}
