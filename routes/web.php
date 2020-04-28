<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\DiemDemo;
use App\User;
use App\Attendance;

Route::get('/point/{id}', function ($id) {
    $diem = DiemDemo::where('id_student', $id)->get();
    if (count($diem) < 1 ) 
    return [
                "code" => 200,
                "id_student" => $id,
                "point" => 0,
            ];
    $diem = $diem->first();
    $result = [
        "code" => 200,
        "id_student" => $id,
        "point" => $diem->point,
    ];
    return json_encode($result);
});

Auth::routes([
    'register' => false,
    'reset' => false, 
    'verify' => false, 
]);

Route::get('users', function(){
     $users = Attendance::all();
     return count($users);
});

Route::get('logout', "LoginController@logout")->name('logout');

Route::get('auth/{provider}', "Auth\LoginController@redirectToProvider")->name('loginGG');

Route::get('auth/{provider}/callback', "Auth\LoginController@handleProviderCallback");

Route::get('excel', "ExcelController@export")->name('export');

Route::group(['middleware' => 'studentMiddleware'], function () {

    Route::get('/', function () {
        return redirect()->route('newActionList');
    });

    Route::post('profile',"ClientController\ProfileController@index")->name('edit_profile');
    Route::get('profile',"ClientController\ProfileController@getProfile")->name('get_profile');

    Route::get('hoat-dong-moi', "ClientController\ActionController@getNewAction")->name('newActionList');

    Route::get('hoat-dong-moi/{id_action}', "ClientController\ActionController@getNewActionDetail")->name('newActionDetail');

    Route::post('hoat-dong-moi/{id_action}', "ClientController\ActionController@postRegisterAction");

    Route::get('hoat-dong', "ClientController\ActionController@getMyAction")->name('myAction');

    Route::get('hoat-dong/chart', "ClientController\ActionController@getMyActionChart")->name('myActionChart');


    Route::group(['prefix' => 'diem-ren-luyen-cua-toi'], function () {

        Route::get('/', "ClientController\MyPointController@getList")->name('myPoint');

        Route::get('/{id_dot}', "ClientController\MyPointController@getDanhGia")->name('getMyDot');

        Route::post('/{id_dot}', "ClientController\MyPointController@postDanhGia");

    });

    Route::group(['prefix' => 'diem-ren-luyen'], function () {

        Route::get('them-moi', "ClientController\DotXetDiemController@getAdd")->name('addPoint');

        Route::post('them-moi', "ClientController\DotXetDiemController@postAdd");

        Route::group(['prefix' => 'danh-sach-dot'], function () {

            Route::group(['middleware' => ['positionMiddleware']], function () {

                Route::get('/', "ClientController\DotXetDiemController@danhSachDot")->name('danh_sach_dot');

                Route::get('chart', "ClientController\DotXetDiemController@danhSachDotChart")->name('chartDRL');

                Route::get('chartPhoDiem', "ClientController\DotXetDiemController@danhSachDotChartPhoDiem")->name('chartPhoDiem');

                Route::get('/delete/{id_dot}', "ClientController\DotXetDiemController@delete")->name('get_xoa_dot');

                Route::get('note/{id_dot}/{id_student}', "ClientController\DotXetDiemController@getNote")->name('getDotNote');

                Route::get('changeStatus/{id_dot}', "ClientController\DotXetDiemController@changeStatus")->name('changeStatus');

                Route::get('/{id_dot}', "ClientController\DotXetDiemController@getDot")->name('getDot');

                Route::get('/{id_dot}_{id_detail}/{name}_{id_student}', "ClientController\PointController@getDanhGia")->name('getDanhGia');

                Route::post('/{id_dot}_{id_detail}/{name}_{id_student}', "ClientController\PointController@postDanhGia");

                Route::get('download/{id_dot}', "ClientController\DotXetDiemController@downloadDotPDF")->name('downDot');

            });
            Route::get('/download/pdf/{id_student}_{id_dot}', "ClientController\PointController@downloadPointPDF")->name('downloadPointPDF');
        });



    });

    Route::group(['prefix' => 'diem-danh', 'middleware' => 'positionMiddleware'], function () {

        Route::get('/', "ClientController\AttendanceController@getList")->name('attendanceList');

        Route::get('{id}', "ClientController\AttendanceController@getAttendance")->name('attendance');

        Route::post('note/{id_action}/{id_student}', "ClientController\AttendanceController@postAttendanceNote")->name('note_attendance');

        Route::get('{id_action}/point/{id_student}', "ClientController\AttendanceController@getPoint");

        Route::get('{id_action}/{id_student}', "ClientController\AttendanceController@postApiAttendance");

    });

    Route::group(['prefix' => 'hoat-dong', 'middleware' => 'positionMiddleware'], function () {

        Route::get('danh-sach', "ClientController\ActionController@getList")->name("actionList");

        Route::get('them-moi', "ClientController\ActionController@getAdd")->name('addAction');

        Route::post('them-moi', "ClientController\ActionController@postAdd");

        Route::get('them-moi/example', "ClientController\ActionController@getExample");

        Route::get('xoa/{id}', "ClientController\ActionController@getDelete")->name('deleteAction');

    });

});
// , 'middleware' => 'adminMiddleware'
Route::group(['prefix' => 'admin', 'middleware' => 'adminMiddleware'], function () {

    Route::get('/', function () {
        return view('admin.index');
    })->name('adminIndex');

    Route::group(['prefix' => 'class'], function () {

        Route::get('/', "AdminController\ClassController@getList")->name('adminListClass');

        Route::get('add', "AdminController\ClassController@getAdd")->name('adminAddClass');

        Route::post('add', "AdminController\ClassController@postAdd");

        Route::get('edit/{class}', "AdminController\ClassController@getEdit")->name('adminEditClass');

        Route::post('edit/{class}', "AdminController\ClassController@postEdit");

    });

    Route::group(['prefix' => 'student'], function () {

        Route::get('/', "AdminController\StudentController@getList")->name('adminListStudent');

        Route::get('add', "AdminController\StudentController@getAdd")->name('adminAddStudent');

        Route::post('add', "AdminController\StudentController@postAdd");

        Route::get('add-excel', "AdminController\StudentController@getAddExcel")->name('add-excel');
        
        Route::post('add-excel', "AdminController\StudentController@postAddExcel");

        Route::get('ajaxList', "AdminController\StudentController@getListAjax")->name('ajaxStudentList');

        Route::get('edit/{id_student}', "AdminController\StudentController@getEdit")->name('editStudent');

        Route::post('edit/{id_student}', "AdminController\StudentController@postEdit");

    });

    Route::group(['prefix' => 'action'], function () {
        
        Route::get('/', "AdminController\ActionController@getList")->name('listActionAD');

        Route::get('chart/{id_action}', "AdminController\ActionController@getChartList")->name('listActionADChart');

        Route::get('chart-action-class/{id_action}/{id_class}', "AdminController\ActionController@getChartActionClass");

        Route::get('chart-category/{id_action}/{id_class}', "AdminController\ActionController@getChartCategory");

        Route::get('add', "AdminController\ActionController@getAdd")->name('addActionAD');

        Route::post('add', "AdminController\ActionController@postAdd");


        Route::group(['prefix' => 'category'], function () {
            
            Route::get('/', "AdminController\ActionController@getListCategory")->name('listCategoryAD');

            Route::get('add', "AdminController\ActionController@getAddCategory")->name('addCategoryAD');

            Route::post('add', "AdminController\ActionController@postAddCategory");

            Route::get('delete/{id}', "AdminController\ActionController@getDeleteCategory")->name('deleteCategoryAD');

        });

    });

    Route::group(['prefix' => 'diem-ren-luyen'], function () {

        Route::get('/', "AdminController\DotXetDiemController@getList")->name('listDRLAD');

        Route::get('them-moi', "AdminController\DotXetDiemController@getAdd")->name('addPointAD');

        Route::post('them-moi', "AdminController\DotXetDiemController@postAdd");

        Route::group(['prefix' => 'thong-ke'], function () {
    
            Route::get('/', "AdminController\DotXetDiemController@getThongKe")->name('thongKeAD');

            Route::get('ajax', "AdminController\DotXetDiemController@getThongKeAJAX")->name('thongKeADAJAX');

        });

        Route::group(['prefix' => 'lop'], function () {
            
            Route::get('{id_class}', "AdminController\DotXetDiemController@getForClass")->name('DRLClassAD');

            Route::get('{id_class}/{id_dot}', "AdminController\DotXetDiemController@getDot")->name('getDotAD');

        });

        Route::group(['prefix' => 'dot-xet-diem'], function () {

            Route::get('{id_dot}', "AdminController\DotXetDiemController@getForDot")->name('DRLDotAD');

        });

        // Route::group(['prefix' => 'danh-sach-dot'], function () {

        //     Route::get('/', "ClientController\DotXetDiemController@danhSachDot")->name('');

        //     Route::get('/delete/{id_dot}', "ClientController\DotXetDiemController@delete")->name('get_xoa_dot');

        //     Route::get('changeStatus/{id_dot}', "ClientController\DotXetDiemController@changeStatus")->name('changeStatus');

        //     Route::get('/{id_dot}', "ClientController\DotXetDiemController@getDot")->name('getDot');

        //     Route::get('/{id_dot}_{id_detail}/{name}_{id_student}', "ClientController\PointController@getDanhGia")->name('getDanhGia');

        //     Route::post('/{id_dot}_{id_detail}/{name}_{id_student}', "ClientController\PointController@postDanhGia");

        //     Route::get('download/{id_dot}', "ClientController\DotXetDiemController@downloadDotPDF")->name('downDot');

        //     Route::get('/download/pdf/{id_student}_{id_dot}', "ClientController\PointController@downloadPointPDF")->name('downloadPointPDF');
        // });



    });

});


Route::get('home', function () {
    return redirect()->route('newActionList');
});
