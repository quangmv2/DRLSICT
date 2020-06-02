<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Khoa Công nghệ thông tin và truyền thông</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon-32x32.png') }}">

    <!-- Bootstrap -->
    <link href="{{ asset('assets/css/login/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset('assets/css/login/all.css') }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset('assets/css/login/nprogress.css') }}" rel="stylesheet">
    <!-- Animate.css -->
    <link href="{{ asset('assets/css/login/animate.min.css') }}" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link rel="stylesheet" href="assets/css/login/stylelogin.css">
    <link href="assets/css/login/custom.min.css" rel="stylesheet">

    

    <style>
       
  </style>
</head>

<body class="login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="" method="POST">
                        {{ csrf_field() }}
                        <h1>Sinh viên</h1>
                        Sinh viên đăng nhập bằng tài khoản email Khoa đã cấp.
                        
                        <br>
                        <div>
                            <center><a class="btn" href="{{ route('loginGG', ['provider'=>'google']) }}">Đăng nhập</a></center>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <p class="change_link">Đến Trang chủ
                                <a href="http://sict.udn.vn" class="to_register"> Khoa Công nghệ thông tin và Truyền thông </a>
                            </p>

                            <div class="clearfix"></div>
                            <br>

                            <div>
                                <h1><i class="fa fa-university"></i> SICT!</h1>
                                <p>©2019 Bản quyền thuộc về Khoa Công nghệ thông tin và Truyền thông - Đại học Đà Nẵng</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>

        </div>
    </div>
     <!-- Load Facebook SDK for JavaScript -->
    {{-- <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
        FB.init({
            xfbml            : true,
            version          : 'v5.0'
        });
        };

        (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    <!-- Your customer chat code -->
    <div class="fb-customerchat"
        attribution=setup_tool
        page_id="1601748876759387"
    theme_color="#fed014"
    logged_in_greeting="Hi! How can we help you?"
    logged_out_greeting="Hi! How can we help you?">
    </div> --}}
</body>
</html>