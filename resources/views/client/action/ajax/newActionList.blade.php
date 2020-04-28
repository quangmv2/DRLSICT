@php
    use App\Attendance;
    use App\ActionRelationshipClass;
@endphp
<table class="table table-striped table-bordered table-hover" style="width: 100%">

    <thead>
        <tr>
            <td>STT</td>
            <td>Tên hoạt động</td>
            <td>Thời gian</td>
            <td>Trạng thái</td>
            <td>Xem</td>
        </tr>
    </thead>

    <tbody id="dataStudent">
        @foreach ($actions as $index => $value)
            <tr>
                @php
                    $value = $value->getAction;
                @endphp
                <td>{{ (($page-1)*20 + $index+1) }}</td>
                <td>{{ $value->name}}</td>
                <td>{{ \Carbon\Carbon::parse($value->time)->format('d-m-Y') }}</td>
                <td> 
                    @php
                        $AR = Attendance::where('id_student', session('account')->id_student)->where('id_action', $value->id_action)->get();
                        $action = ActionRelationshipClass::where('id_action', $value->id_action)->where('id_class', session('account')->id_class)->get();
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
                <td><a href="{{ route('newActionDetail', ['id_action' => $value->id_action]) }}"><i class="fas fa-eye" style="color: green"></i></a>  </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{ $actions->links() }}