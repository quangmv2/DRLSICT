<script>
  function toggleNav() {
    const nav = document.getElementById('accordionSidebar');
    nav.classList.toggle('nav-custom');
  }
</script>
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow ">

    <div class="">
        <i class="fa fa-bars" id="" onclick="toggleNav()"style="cursor: pointer"></i>
      </div>

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">

    
    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">@if (Auth::check())
          {{ Auth::user()->profile->first_name." ".Auth::user()->profile->last_name }}
         @endif</span>
        <img class="img-profile rounded-circle" src="@if (session('account') && Auth::check())
        {{ session('account')->picture }}
     @endif" alt="@if (Auth::check())
        {{ Auth::user()->profile->first_name." ".Auth::user()->profile->last_name }}
      @endif">
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" data-toggle="modal" data-target="#form" style="cursor: pointer">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Thông tin cá nhân
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Đăng xuất
        </a>
      </div>
    </li>
  </ul>

</nav>