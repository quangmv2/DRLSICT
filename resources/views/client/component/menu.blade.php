<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion show-nav" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
    <div class="sidebar-brand-icon ">
      <i class="fas fa-university"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SICT</div>
  </a>
  <style>

  </style>
  <div class="row profile">
    <div class="col-sm-3" style="padding: 0;
    padding-left: 5%;
    margin: auto;">
        <img class="img-profile rounded-circle" style="width: 100%;" src="http://daotao.sict.udn.vn/public/gv/images/img.jpg" alt="">
    </div>
    <div class="col-sm-8" style="padding: 0">
        <p class="m-auto">Xin chào,</p>
        <h5 style="color: white">@if (Auth::check())
          {{ Auth::user()->profile->first_name." ".Auth::user()->profile->last_name }}
      @endif</h5>
    </div>
  </div>
 



  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="http://sict.udn.vn/">
      <i class="fas fa-fw fa-home"></i>
      <span>Trang chủ</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">


  <!-- Nav Item - Pages Collapse Menu -->
  <li class="nav-item hrefLink">
    <a class="nav-link collapsed" href="{{ route('newActionList') }}">
      <i class="fas fa-fw fa-cog"></i>
      <span>Hoạt động mới</span>
    </a>
  </li>
  <li class="nav-item hrefLink">
    <a class="nav-link collapsed" href="{{ route('myAction') }}">
      <i class="fas fa-fw fa-cog"></i>
      <span>Hoạt động của tôi</span>
    </a>
  </li>
  <li class="nav-item hrefLink">
    <a class="nav-link collapsed" href="{{ route('myPoint') }}">
      <i class="fas fa-fw fa-cog"></i>
      <span>Điểm rèn luyện của tôi</span>
    </a>
  </li>
  <hr class="sidebar-divider">
  
  @if (Auth::check() && Auth::user()->profile->student->id_position != 6)
    {{-- <li class="nav-item" >
      <a class="nav-link collapsed" href="{{ route('attendanceList') }}">
        <i class="fas fa-fw fa-cog"></i>
        <span>Điểm danh</span>
      </a>
    </li> --}}
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse">
        <i class="fas fa-fw fa-cog"></i>
        <span>Quản lý hoạt động</span>
      </a>
      <div id="collapse1" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{ route('attendanceList') }}">Điểm danh hoạt động</a>
          <a class="collapse-item" href="{{ route('actionList') }}">Danh sách hoạt động</a>
          <a class="collapse-item" href="{{ route('addAction') }}">Thêm hoạt động</a>
        </div>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="true" aria-controls="collapse">
        <i class="fas fa-fw fa-cog"></i>
        <span>Quản lý điểm rèn luyện</span>
      </a>
      <div id="collapse2" class="collapse" aria-labelledby="heading2" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="{{ route('danh_sach_dot') }}">Danh sách đợt</a>
        </div>
      </div>
    </li>
    <hr class="sidebar-divider d-none d-md-block" > 
  @endif
  
  <li class="nav-item hrefLink">
    <a class="nav-link collapsed" href="https://facebook.com/vanquang312" target="_blank">
      <i class="fas fa-fw fa-cog"></i>
      <span>Liên hệ khi có sự cố</span>
    </a>
  </li>
  <hr class="sidebar-divider d-none d-md-block" id="bottomMenu">
  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline" id="">
    <button class="rounded-circle border-0" style="display: none"></button>
  </div>
  <script>
    let bottomMenu = document.getElementById('bottomMenu');
    let menu = document.getElementById('accordionSidebar');
    document.addEventListener('scroll', function(e) {
      let list = menu.classList.value
      if (window.scrollY - 10 >= bottomMenu.offsetTop) {
        menu.style.display = 'none'
      } else {
        menu.style.display = 'block'
      }
    });
  </script>
</ul>