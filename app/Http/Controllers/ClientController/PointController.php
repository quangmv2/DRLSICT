<?php

namespace App\Http\Controllers\ClientController;

use App\Http\Controllers\ClientController\ClientController;
use Illuminate\Http\Request;
use App\Http\Requests\PointRequest;

use GuzzleHttp\Client;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\File;
use ZipArchive;
use Illuminate\Support\Facades\Storage; 

use App\Classs;
use App\Student;
use App\DotXetDiem;
use App\SchoolYear;
use App\Point;
use App\MyPoint;
use App\Attendance;
use App\Diem;

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
        
        $my_point = MyPoint::where('id_dot', $id_dot)
        ->where('id_student', $id_student)
        ->get()->first();

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
            Point::where('id_dot', $id_dot)
            ->where('id_student', $id_student)
            ->update($arr_update);
            updateTotal($my_point->id_point);
        }

        $arr_update = [
            "p1dd" => convertPointToPoint($point_study),
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
        // return $my_temp_point;

        if (empty($my_point) || empty($my_temp_point) || empty($points)) return abort(404);
        return view('client.point.danh_gia', [
            'my_point' => $my_point,
            'my_temp_point' => $my_temp_point,
            'students' => $points,
            'index' => $vt,
            'point' => $point_study,
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
        return $pdf->stream("Phiếu đánh giá kết quả rèn luyện sinh viên ".$student->first_name." ".$student->last_name." - Học kỳ: ".hocKy($dot->hoc_ki)." - Năm học: ". $dot->nam_hoc.'.pdf');
    }

    public function downloadAllPointPDF(Request $request, $id_dot)
    {
        date_default_timezone_set('Australia/Melbourne');
        $date = date('m_d_Y_h_i_s', time());
        $id_class = $request->session()->get('account')->id_class;
        $students = Point::where('id_dot', $id_dot)
        ->join('students', 'students.id_student', '=', 'points.id_student')
        ->join('profiles', 'profiles.id_profile', '=', 'students.id_profile')
        ->where('students.id_class', $id_class)
        // ->where('points.status', '>', 0)
        ->orderby('students.id_student')
        ->select('students.*', 'points.total', 'points.note', 'profiles.first_name', 'profiles.last_name')
        ->get();
        $zip = new ZipArchive;
        Storage::makeDirectory('zip');
        $path = 'pdf';
        $fileName = 'app/zip/'.$id_class."_".$date.'.zip';
        if ($zip->open(storage_path($fileName), ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE)
        {
            $path_name = $path."/".$id_class."_".$date;
            Storage::makeDirectory($path."/".$id_class."_".$date);
            foreach ($students as $key => $student) {
                $name = $this->savePDF($path_name, $id_dot, $student->id_student, $id_class);
                // $file = File::get($name);
                // $relativeNameInZipFile = basename($file);
                $zip->addFile($name, $student->id_student.'.pdf');
            }
            $zip->close();
        }
        return response()->download(storage_path($fileName));
    }

    public function savePDF($path_name, $id_dot, $id_student, $id_class)
    {
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
        
        $name = storage_path('app/'.$path_name)."/".$id_student.'.pdf';
        $pdf->save($name);
        return $name;
    }

}
