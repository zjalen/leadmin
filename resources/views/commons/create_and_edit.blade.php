<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>后台管理</title>
    {{--<link rel="stylesheet" href="{{mix('css/app.css')}}">--}}
    <link rel="stylesheet" href="{{asset('vendor/leadmin/css/theme/index.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/leadmin/css/theme/display.css')}}">

    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            height: 100vh;
            margin: 0;
        }
        a{
            text-decoration:none
        }
    </style>
</head>
<body>
<div id="app">
    <common-form :form_data="{{ $data }}"></common-form>
</div>
<script src="{{asset('vendor/leadmin/js/app.js')}}"></script>
{{--<script src="{{mix('js/app.js')}}"></script>--}}
</body>
</html>