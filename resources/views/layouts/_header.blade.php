<!--header start-->
<header class="header white-bg">
    <div class="sidebar-toggle-box">
        <a href="javascript:"><div data-original-title="隐藏/打开菜单" data-placement="right" class="fa fa-reorder tooltips"></div></a>
    </div>
    <!--logo start-->
    <a href="#" class="logo">后台<span>管理</span></a>
    <!--logo end-->
    <div class="nav notify-row" id="top_menu">
        <!--  notification start -->
        <ul class="nav top-menu">
            <!-- notification dropdown start-->
            <li id="header_notification_bar" class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                    <i class="fa fa-bell"></i>
                    <span class="badge bg-warning">7</span>
                </a>
                <ul class="dropdown-menu extended notification">
                    <div class="notify-arrow notify-arrow-yellow"></div>
                    <li>
                        <p class="yellow">You have 7 new notifications</p>
                    </li>
                    <li>
                        <a href="#">
                            <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                            Server #3 overloaded.
                            <span class="small italic">34 mins</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="label label-warning"><i class="fa fa-bell"></i></span>
                            Server #10 not respoding.
                            <span class="small italic">1 Hours</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="label label-success"><i class="fa fa-plus"></i></span>
                            New user registered.
                            <span class="small italic">Just now</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="label label-info"><i class="fa fa-bullhorn"></i></span>
                            Application error.
                            <span class="small italic">10 mins</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">See all notifications</a>
                    </li>
                </ul>
            </li>
            <!-- notification dropdown end -->
        </ul>
        <!--  notification end -->
    </div>
    <div class="top-nav ">
        <!--search & user info start-->
        <ul class="nav pull-right top-menu">
            <li>
                <input type="text" class="form-control search" style="background: url('{{ asset("vendor/leadmin/images/search-icon.jpg") }}') no-repeat 10px 8px;" placeholder="Search">
            </li>
            <!-- user login dropdown start-->
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <img style="width: 28px;height: 28px;" src="{{ asset(\Zjalen\Leadmin\Facades\Leadmin::user()->avatar ?: 'vendor/leadmin/images/avatar-default.jpg') }}">
                    <span class="username">{{ \Zjalen\Leadmin\Facades\Leadmin::user()->name }}</span>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu extended logout">
                    <div class="log-arrow-up"></div>
                    <li><a href="#"><i class=" fa fa-suitcase"></i>事项</a></li>
                    <li><a href="#"><i class="fa fa-cog"></i> 设置</a></li>
                    <li><a href="#"><i class="fa fa-bell"></i> 通知</a></li>
                    <li><a href="{{ url('admin/auth/logout') }}"><i class="fa fa-key"></i>注销</a></li>
                </ul>
            </li>
            <!-- user login dropdown end -->
        </ul>
        <!--search & user info end-->
    </div>
</header>
<!--header end-->