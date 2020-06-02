<?php

use App\User;
use App\Point;
use App\MyPoint;

function utf8convert($str) {

    if(!$str) return false;

    $utf8 = [

            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

            'd'=>'đ|Đ',

            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

            'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',

            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ'];

    foreach($utf8 as $ascii=>$uni) $str = preg_replace("/($uni)/i",$ascii,$str);

    return $str;

}

function utf8tourl($text) {
    $text = strtolower(utf8convert($text));
    // echo $text;
    // die();
    // $text = str_replace( "ß", "ss", $text);
    // $text = str_replace( "%", "", $text);
    // $text = preg_replace("/[^_a-zA-Z0-9 -] /", "",$text);
    // $text = str_replace(array('%20', ' '), '-', $text);
    // $text = str_replace("----","-",$text);
    // $text = str_replace("---","-",$text);
    // $text = str_replace("--","-",$text);
    $str = "";
    // echo $text;
    // die();
    for ($i = 0; $i < strlen($text); $i++) {
        $k = ord($text[$i]);
        if (($k<=122 && $k>=97) || ($text[$i] == " ")) {
            $str .=$text[$i];
        }else{

        }
    }
    return $str;
}

function createEmailStudent($text, $class)
{
    $text = (string) utf8tourl($text);
    $text = trim($text);
    $k = explode(' ', $text);
    $name = "";
    $class = strtolower($class);
    for ($i=0; $i < count($k) - 1; $i++) {
        $name .= $k[$i][0];
    }
    $name.=$k[count($k) - 1];

    $user = User::where('email', 'like', $name."%".".".$class."%")->get();
    if (!count($user)>0) return $name.".".$class."@sict.udn.vn";


    $email1 = $user[count($user) - 1]->email;

    $name1 = explode('@', $email1);

    if ($name1 == $name) return $name."1.".$class."@sict.udn.vn";
    $number = (int)$name1[count($name1) - 1];

    return $name.($number + 1).".".$class."@sict.udn.vn";
}

function tenKhongDau($name){
    $name = utf8tourl($name);
    $arr = explode(' ', $name);
    $name = "";
    foreach ($arr as $value){
        $name.=$value."-";
    }
    return substr($name, 0, -1);
}

function danhGia($diem)
{
    if ($diem >= 90) return "Xuất sắc";
    else if ($diem >= 80) return "Tốt";
    else if ($diem >= 65) return "Khá";
    else if ($diem >= 50) return "Trung bình";
    else if ($diem >= 40) return "Yếu";
    return "Kém";
}

function updateTotal($id_point)
{

    $point = Point::find($id_point);
    $total = $point->p1a
    + $point->p1b1
    + $point->p1b2
    + $point->p1c
    + $point->p1d
    + $point->p1dd
    + $point->p2a1
    + $point->p2a2
    + $point->p2b1
    + $point->p2b2
    + $point->p3a1
    + $point->p3a2
    + $point->p3b1
    + $point->p3c
    + $point->p4a1
    + $point->p4a2
    + $point->p4a3
    + $point->p4b
    + $point->p4c
    + $point->p5a
    + $point->p5b
    + $point->p5c
    + $point->p5d;

    Point::where('id_point', $id_point)->update(['total'=>$total]);
}

function updateTotalMyPoint($id_point)
{

    $point = MyPoint::find($id_point);
    $total = $point->p1a
    + $point->p1b1
    + $point->p1b2
    + $point->p1c
    + $point->p1d
    + $point->p1dd
    + $point->p2a1
    + $point->p2a2
    + $point->p2b1
    + $point->p2b2
    + $point->p3a1
    + $point->p3a2
    + $point->p3b1
    + $point->p3c
    + $point->p4a1
    + $point->p4a2
    + $point->p4a3
    + $point->p4b
    + $point->p4c
    + $point->p5a
    + $point->p5b
    + $point->p5c
    + $point->p5d;

    MyPoint::where('id_my_point', $id_point)->update(['total'=>$total]);
}

function convertPointToPoint($point){

    if ($point >= 3.2) return 4;
    if ($point >= 2.0) return 2;
    if ($point >= 0) return 0;
    return 0;

}

function hocKy($hocky)
{
    if ($hocky == 1) {
        return 'I';
    }
    return "II";
}

use App\Classs;
use App\Profile;
use App\Student;

function createAccountLogin($users)
{
    $email = $users->email;
    $arr = explode('@', $email);
    $arrClass = explode('.', $arr[0]);
    if (empty($arrClass[1])) return 0;
    $arrClass['user'] = $users;
    echo json_encode($arrClass);
    $class = mb_strtoupper($arrClass[1]);

    $cl = Classs::where('id_class', $class)->get();
    if (count($cl) < 1){
        return 0;
    }
    $user = new User;
    $user->email = $email;        
    $user->password =  password_hash(1, PASSWORD_BCRYPT);
    $user->type = 0;
    $user->save();

    $profile = new Profile;
    $profile->first_name = $users->family_name;
    $profile->last_name = $users->given_name;
    $profile->email = $email;
    $profile->save();

    $student = new Student;
    $student->id_profile = $profile->id;
    $student->id_student = strtoupper($request->input('id_student'));
    $student->id_class = $request->input('class');
    $student->id_position = $request->input('position');
    $student->save();

    return 1;

}

use Illuminate\Support\Facades\Auth;

function isAuth()
{
    if (Auth::check() && Auth::user()->profile->student->id_position <= 5) return 1;
    return 0;
}
function renderKQ($point)
{
    return $point;
}

?>
