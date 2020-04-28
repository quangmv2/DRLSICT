<table class="table table-striped table-bordered table-hover" style="width: 100%">

    <thead>
        <tr>
            <td>STT</td>
            <td>Tên hoạt động</td>
            <td>Thời gian</td>
            <td>Trạng thái</td>
            <td>Kiểu</td>
            <td>Xem</td>
        </tr>
    </thead>

    <tbody id="dataStudent">
        @foreach ($actions as $index => $value)
            <tr>
                <td>{{ (($page-1)*20 + $index+1) }}</td>
                <td>{{ $value->name}}</td>
                <td>{{ \Carbon\Carbon::parse($value->time)->format('d-m-Y') }}</td>
                <td> 
                    @php
                        $AR = App\Attendance::where('id_student', session('account')->id_student)->where('id_action', $value->id_action)->get();
                        $action = App\ActionRelationshipClass::where('id_action', $value->id_action)->where('id_class', session('account')->id_class)->get();
                        if (count($AR) < 1 || count($action) < 1) {
                            echo "Không tham gia";
                        } else {
                            $AR = $AR[0];
                            $action = $action->first();
                            if ($action->confirm == 0) {
                                echo "Chưa điểm danh";
                            } else {
                                 if ($AR->status == 0) {
                                    echo "Vắng";
                                } else {
                                    echo "Đã tham gia - Đã điểm danh";
                                }
                            }
                            
                           
                        }
                    @endphp 
                </td>
                <td> 
                    @if ($value->type == 0)
                        Cả lớp
                    @else
                        @if ($value->type == 1)
                            Bắt buộc
                        @else
                            Đăng ký
                        @endif
                    @endif 
                </td>
                <td><a href=""><i class="fas fa-eye" style="color: green"></i></a>  </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $actions->links() }}