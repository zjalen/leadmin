<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Jalen">
    <meta name="keyword" content="">

    <title>后台管理</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor/leadmin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/leadmin/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/leadmin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/leadmin/css/style-responsive.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset("vendor/leadmin/css/tab-style.css") }}">

    <style>
        @media (max-width: 768px) {
            #main-content {
                padding-top: 61px;
                height: calc(100vh);
            }
        }
        @media (min-width: 769px) {
            #main-content {
                padding-top: 61px;
                height: calc(100vh - 45px);
            }
        }
        .dropdown-menu>li>a>.glyphicon, .dropdown-menu>li>a>.fa, .dropdown-menu>li>a>.ion {
            margin-right: 10px;
        }
        ul, .tab-menu>li>a {
            border-radius: 3px;
            color: inherit;
            line-height: 25px;
            margin: 4px;
            text-align: left;
            font-weight: 400;
        }
    </style>
</head>

<body style="overflow-x: hidden;">
    <section id="container" class="">

    @include('leadmin.layouts._header')

    @include('leadmin.layouts._sidebar')

    <!--main content start-->
    <section id="main-content" style="">
        <div id="" class="gray-bg dashbard-1 content-wrapper" style="width: 100%;height: 100%;">
            <div class="row content-tabs" style="margin-left: 0px">
                <button class="roll-nav roll-left J_tabLeft"><i class="fa fa-backward"></i>
                </button>
                <nav class="page-tabs J_menuTabs">
                    <div class="page-tabs-content">
                        <a href="javascript:;" class="active J_menuTab" data-id="index_v1.html">首页</a>
                    </div>
                </nav>
                <button class="roll-nav roll-right J_tabRight" style="right: 95px;"><i class="fa fa-forward"></i>
                </button>
                <div class="btn-group roll-nav roll-right" style="right: 15px">
                    <button class="dropdown J_tabClose" data-toggle="dropdown">常用操作<span class="caret"></span>

                    </button>
                    <ul role="menu" class="tab-menu dropdown-menu dropdown-menu-right">
                        <li class="J_tabGo"><a><i class="fa fa-arrow-right"></i>前进</a>
                        </li>
                        <li class="J_tabBack"><a><i class="fa fa-arrow-left"></i>后退</a>
                        </li>
                        <li class="J_tabFresh"><a><i class="fa fa-refresh"></i>刷新</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabShowActive"><a><i class="fa fa-location-arrow"></i>定位当前选项卡</a>
                        </li>
                        <li class="divider"></li>
                        <li class="J_tabCloseAll"><a><i class="fa fa-close"></i>关闭全部选项卡</a>
                        </li>
                        <li class="J_tabCloseOther"><a><i class="fa fa-tag"></i>关闭其他选项卡</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="J_mainContent" id="content-main" style="height: 100%;">
                <iframe class="J_iframe" name="iframe0" style="width: 100%;" src="{{ url('admin/welcome') }}" frameborder="0" data-id="index_v1.html" seamless>
                </iframe>
            </div>
        </div>
    </section>
    {{--<div style="padding: 5px;">--}}
        {{--@include('leadmin.layouts._footer')--}}
    {{--</div>--}}

    <script src="{{ asset('vendor/leadmin/js/jquery.js') }}"></script>
    <script src="{{ asset('vendor/leadmin/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset("vendor/leadmin/js/contabs.js") }}"></script>
    <script src="{{ asset('vendor/leadmin/js/common-scripts.js') }}"></script>

    </section>
<script>
    $(document).ready(function () {
        $(".sidebar-menu a").click(function () {
            if (!$(this).parent().hasClass("sub-menu")) {
                $(".sidebar-menu .active").removeClass("active");
                $(this).parent().addClass("active");
            }
        });
    });
</script>

</body>
</html>
