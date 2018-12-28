<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" style="height: 100%;overflow: scroll;">
            <li class="active">
                <a href="{{ url('admin/welcome') }}" class="J_menuItem">
                    <i class="fa fa-dashboard"></i>
                    <span>仪表盘</span>
                </a>
            </li>
            @each('leadmin.layouts._menu', $tree, 'item')
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->