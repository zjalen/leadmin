<!doctype html>
<html lang="zh_CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>脚手架工具</title>

    <!-- Fonts -->
    {{--<link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">--}}

    <!-- Styles -->
    <!-- 引入样式 -->
    <link rel="stylesheet" href="{{ asset("vendor/leadmin/css/theme/index.css") }}">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: flex-start;
            flex-direction: column;
        }

        .demo-block {
            border-radius: 3px;
            transition: .2s;
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

    </style>
</head>
<body>
    <div id="app" class="flex-center position-ref">
        <page-scaffold></page-scaffold>
    </div>

    <script src="{{ asset("vendor/leadmin/js/app.js") }}"></script>
{{--<script src="{{mix('js/app.js')}}"></script>--}}
</body>
</html>