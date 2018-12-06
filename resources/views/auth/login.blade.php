<!doctype html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>后台登录</title>

    <!-- Fonts -->

    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{asset('vendor/leadmin/css/theme/index.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/leadmin/css/theme/display.css')}}">
    <style>
        html, body {
            /*background-color: rgba(16, 13, 84, 1);*/
            color: #636b6f;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        #particles-js {
            position: absolute;
            background: rgba(10, 0, 43, 0.83);
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            overflow: hidden;
            z-index: -1;
            height: 100%;
        }

        .position-ref {
            position: relative;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
        .full-width {
            width: 100%;
            padding: 0 10px;
        }

        .full-height {
            height: 100vh;
        }
        .login-box {
            max-width: 500px;
            color: #fff;
        }
        .box-title {
            text-align: center;
            margin-bottom: 30px;
        }

    </style>
</head>
<body>
<div id="particles-js"><canvas class="particles-js-canvas-el" style="width: 100%; height: 100%;"></canvas></div>
<div id="app" class="position-ref">
    <page-login :src="{ url: '{{ captcha_src() }}'}"></page-login>
</div>
<script src="{{ asset('vendor/leadmin/js/app.js') }}"></script>
{{--<script src="{{mix('js/app.js')}}"></script>--}}
<script src="{{ asset('vendor/leadmin/js/particles.min.js') }}"></script>
<script>
    particlesJS.load('particles-js', '{{ asset('vendor/leadmin/js/particles.json') }}', function() {
        // console.log('callback - particles.js config loaded');
    });
</script>

</body>
</html>
